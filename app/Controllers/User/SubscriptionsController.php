<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;

class SubscriptionsController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $user = DB::fetch('SELECT subscription_expires_at FROM users WHERE id = :id', ['id' => Auth::id()]);
        $this->render('user/subscriptions/index', [
            'title' => 'Subscriptions',
            'expires' => $user['subscription_expires_at'] ?? null,
        ], 'user');
    }
}