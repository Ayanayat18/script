<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $users = DB::fetchAll('SELECT id, name, email, role, status, wallet_balance, subscription_expires_at, created_at FROM users ORDER BY id DESC');
        $this->render('admin/users/index', [
            'title' => 'Users',
            'users' => $users,
        ], 'admin');
    }
}