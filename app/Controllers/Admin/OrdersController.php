<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class OrdersController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $orders = DB::fetchAll('SELECT o.*, u.email, s.name AS service_name FROM orders o JOIN users u ON u.id = o.user_id JOIN services s ON s.id = o.service_id ORDER BY o.id DESC LIMIT 200');
        $this->render('admin/orders/index', [
            'title' => 'Orders',
            'orders' => $orders,
        ], 'admin');
    }
}