<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

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
}