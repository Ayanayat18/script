<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;

class NotificationsController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $items = DB::fetchAll('SELECT * FROM notifications WHERE user_id = :id OR user_id IS NULL ORDER BY id DESC LIMIT 100', ['id' => Auth::id()]);
        $this->render('user/notifications/index', [
            'title' => 'Notifications',
            'items' => $items,
        ], 'user');
    }
}