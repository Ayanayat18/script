<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Payments\PaypalGateway;
use App\Core\CSRF;

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
        if ($amount <= 0) { $this->redirect('/wallet'); }
        $gateway = new PaypalGateway();
        $res = $gateway->createPayment(Auth::id(), $amount);
        if (($res['status'] ?? '') === 'redirect') {
            header('Location: ' . $res['redirect_url']);
            exit;
        }
        $this->redirect('/wallet');
    }
}