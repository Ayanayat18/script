<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

class WalletController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $txs = DB::fetchAll('SELECT w.*, u.email FROM wallet_transactions w JOIN users u ON u.id = w.user_id ORDER BY w.id DESC LIMIT 200');
        $this->render('admin/wallet/index', [
            'title' => 'Wallet',
            'txs' => $txs,
        ], 'admin');
    }

    public function adjustForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $this->render('admin/wallet/adjust', ['title' => 'Adjust Wallet'], 'admin');
    }

    public function adjust(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $userId = (int)($_POST['user_id'] ?? 0);
        $amount = (float)($_POST['amount'] ?? 0);
        $type = $amount >= 0 ? 'credit' : 'debit';
        if ($userId > 0 && $amount != 0.0) {
            DB::query('UPDATE users SET wallet_balance = wallet_balance + :amt WHERE id = :id', ['amt' => $amount, 'id' => $userId]);
            DB::insert('INSERT INTO wallet_transactions (user_id, type, method, amount, reference, created_at) VALUES (:id,:t,\'manual\',:amt,:ref,NOW())', [
                'id' => $userId, 't' => $type, 'amt' => abs($amount), 'ref' => 'ADMIN',
            ]);
        }
        $this->redirect('/admin/wallet');
    }
}