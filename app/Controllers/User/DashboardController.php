<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;

class DashboardController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $userId = Auth::id();
        $user = DB::fetch('SELECT * FROM users WHERE id = :id', ['id' => $userId]);
        $ordersCount = (int) (DB::fetch('SELECT COUNT(*) AS c FROM orders WHERE user_id = :id', ['id' => $userId])['c'] ?? 0);

        $this->render('user/dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'ordersCount' => $ordersCount,
        ], 'user');
    }
}