<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Payments\PaypalGateway;
use App\Payments\BkashGateway;
use App\Payments\NagadGateway;
use App\Payments\RocketGateway;
use App\Payments\BinancePayGateway;
use App\Core\CSRF;
use App\Core\Notifier;

class WalletController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $user = DB::fetch('SELECT wallet_balance FROM users WHERE id = :id', ['id' => Auth::id()]);
        $txs = DB::fetchAll('SELECT * FROM wallet_transactions WHERE user_id = :id ORDER BY id DESC LIMIT 100', ['id' => Auth::id()]);
        $this->render('user/wallet/index', [
            'title' => 'Wallet',
            'balance' => (float)($user['wallet_balance'] ?? 0),
            'txs' => $txs,
        ], 'user');
    }

    public function addFunds(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        if (!CSRF::validate($_POST['_token'] ?? '')) {
            http_response_code(419); echo 'Invalid token'; return;
        }
        $amount = (float)($_POST['amount'] ?? 0);
        $method = (string)($_POST['method'] ?? 'paypal');
        if ($amount <= 0) { $this->redirect('/wallet'); }
        $gateway = match ($method) {
            'bkash' => new BkashGateway(),
            'nagad' => new NagadGateway(),
            'rocket' => new RocketGateway(),
            'binance' => new BinancePayGateway(),
            default => new PaypalGateway(),
        };
        $res = $gateway->createPayment(Auth::id(), $amount, []);
        if (($res['status'] ?? '') === 'redirect') {
            header('Location: ' . $res['redirect_url']);
            exit;
        }
        $this->redirect('/wallet');
    }

    public function callback(): void
    {
        // Generic callback handler (stub)
        if (!Auth::check()) { $this->redirect('/login'); }
        $status = (string)($_GET['status'] ?? 'success');
        $amount = (float)($_GET['amount'] ?? 0);
        if ($status === 'success' && $amount > 0) {
            DB::query('UPDATE users SET wallet_balance = wallet_balance + :amt WHERE id = :id', ['amt' => $amount, 'id' => Auth::id()]);
            DB::insert('INSERT INTO wallet_transactions (user_id, type, method, amount, reference, created_at) VALUES (:uid,\'credit\',:m,:amt,:ref,NOW())', [
                'uid' => Auth::id(), 'm' => 'gateway', 'amt' => $amount, 'ref' => 'PG',
            ]);
            Notifier::notify(Auth::id(), 'Wallet Recharged', 'Your wallet has been credited by $' . number_format($amount, 2));
        }
        $this->redirect('/wallet');
    }
}