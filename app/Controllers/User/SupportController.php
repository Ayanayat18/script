<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\Telegram;

class SupportController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $this->render('user/support/index', [
            'title' => 'Support',
        ], 'user');
    }

    public function send(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if ($subject && $message) {
            $payload = "Support message\nSubject: {$subject}\nFrom: " . (Auth::user()['email'] ?? 'N/A') . "\n\n{$message}";
            Telegram::send($payload);
        }
        $this->render('user/support/index', [
            'title' => 'Support',
            'success' => 'Your message has been sent.',
        ], 'user');
    }
}