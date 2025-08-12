<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);

        $totals = [
            'users' => (int) DB::fetch('SELECT COUNT(*) AS c FROM users')['c'] ?? 0,
            'orders' => (int) DB::fetch('SELECT COUNT(*) AS c FROM orders')['c'] ?? 0,
            'revenue' => (float) (DB::fetch('SELECT COALESCE(SUM(CASE WHEN amount < 0 THEN 0 ELSE amount END),0) AS s FROM wallet_transactions')['s'] ?? 0),
        ];

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'totals' => $totals,
        ], 'admin');
    }
}