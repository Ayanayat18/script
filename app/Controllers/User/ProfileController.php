<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Core\CSRF;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $user = DB::fetch('SELECT id, name, email FROM users WHERE id = :id', ['id' => Auth::id()]);
        $this->render('user/profile/index', [
            'title' => 'Profile',
            'user' => $user,
        ], 'user');
    }
}