<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;

class OrdersController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $userId = Auth::id();
        $orders = DB::fetchAll('SELECT o.*, s.name AS service_name FROM orders o JOIN services s ON s.id = o.service_id WHERE o.user_id = :uid ORDER BY o.id DESC', ['uid' => $userId]);
        $this->render('user/orders/index', [
            'title' => 'My Orders',
            'orders' => $orders,
        ], 'user');
    }
}