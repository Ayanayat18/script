<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class ReportsController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $totals = [
            'credits' => (float) (DB::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM wallet_transactions WHERE type = 'credit'")['s'] ?? 0),
            'debits' => (float) (DB::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM wallet_transactions WHERE type = 'debit'")['s'] ?? 0),
            'orders' => (int) (DB::fetch('SELECT COUNT(*) AS c FROM orders')['c'] ?? 0),
        ];
        $recent = DB::fetchAll("SELECT DATE(created_at) d, SUM(CASE WHEN type='credit' THEN amount ELSE 0 END) credits, SUM(CASE WHEN type='debit' THEN amount ELSE 0 END) debits FROM wallet_transactions GROUP BY DATE(created_at) ORDER BY d DESC LIMIT 14");
        $this->render('admin/reports/index', [
            'title' => 'Reports',
            'totals' => $totals,
            'recent' => $recent,
        ], 'admin');
    }
}