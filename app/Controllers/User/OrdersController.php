<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Core\Pagination;

class OrdersController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $userId = Auth::id();
        $total = (int)(DB::fetch('SELECT COUNT(*) c FROM orders WHERE user_id = :uid', ['uid' => $userId])['c'] ?? 0);
        $pg = Pagination::resolve($total, 20);
        $orders = DB::fetchAll('SELECT o.*, s.name AS service_name FROM orders o JOIN services s ON s.id = o.service_id WHERE o.user_id = :uid ORDER BY o.id DESC LIMIT :lim OFFSET :off', ['uid' => $userId, 'lim' => $pg['perPage'], 'off' => $pg['offset']]);
        $this->render('user/orders/index', [
            'title' => 'My Orders',
            'orders' => $orders,
            'page' => $pg['page'],
            'pages' => $pg['pages'],
        ], 'user');
    }
}