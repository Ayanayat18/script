<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

class UsersController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $users = DB::fetchAll('SELECT id, name, email, role, status, wallet_balance, price_markup_percent, subscription_expires_at, created_at FROM users ORDER BY id DESC');
        $this->render('admin/users/index', [
            'title' => 'Users',
            'users' => $users,
        ], 'admin');
    }

    public function subscriptionForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $this->render('admin/users/subscription', ['title' => 'User Subscription'], 'admin');
    }

    public function updateSubscription(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $userId = (int)($_POST['user_id'] ?? 0);
        $months = (int)($_POST['months'] ?? 0);
        if ($userId > 0 && in_array($months, [3,6,12], true)) {
            DB::query("UPDATE users SET subscription_expires_at = DATE_FORMAT(GREATEST(COALESCE(subscription_expires_at, CURDATE()), CURDATE()), '%Y-%m-%d') + INTERVAL :m MONTH, status = 1 WHERE id = :id", ['m' => $months, 'id' => $userId]);
        }
        $this->redirect('/admin/users');
    }
}