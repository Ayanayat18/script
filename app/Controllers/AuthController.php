<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;
use App\Core\Auth;
use App\Core\CSRF;
use App\Core\View;
use App\Core\Mailer;
use App\Core\TOTP;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login', ['title' => 'Login'], 'auth');
    }

    public function login(): void
    {
        if (!CSRF::validate($_POST['_token'] ?? '')) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $totp = trim($_POST['totp'] ?? '');

        $user = DB::fetch('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->render('auth/login', ['error' => 'Invalid credentials'], 'auth');
            return;
        }
        if ((int)$user['status'] !== 1) {
            $this->render('auth/login', ['error' => 'Account is inactive or expired'], 'auth');
            return;
        }
        if (!empty($user['two_factor_secret'])) {
            if ($totp === '' || !TOTP::verifyCode($user['two_factor_secret'], $totp)) {
                $this->render('auth/login', ['error' => 'Invalid 2FA code'], 'auth');
                return;
            }
        }

        Auth::login($user);
        if (in_array($user['role'], ['admin', 'super_admin'], true)) {
            $this->redirect('/admin');
        } else {
            $this->redirect('/dashboard');
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/login');
    }

    public function forgotForm(): void
    {
        $this->render('auth/forgot', ['title' => 'Forgot Password'], 'auth');
    }

    public function sendReset(): void
    {
        if (!CSRF::validate($_POST['_token'] ?? '')) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        $email = trim($_POST['email'] ?? '');
        $user = DB::fetch('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);
            DB::query('UPDATE users SET reset_token = :t, reset_expires_at = :e WHERE id = :id', [
                't' => $token,
                'e' => $expires,
                'id' => $user['id'],
            ]);
            $link = (defined('APP_URL') ? rtrim(APP_URL, '/') : '') . '/reset?token=' . urlencode($token);
            $html = '<p>Click the link below to reset your password:</p><p><a href="' . View::e($link) . '">Reset Password</a></p>';
            Mailer::send($email, SITE_NAME . ' - Password Reset', $html);
        }
        $this->render('auth/forgot', ['success' => 'If the email exists, a reset link has been sent.'], 'auth');
    }

    public function resetForm(): void
    {
        $token = (string)($_GET['token'] ?? '');
        $this->render('auth/reset', ['token' => $token], 'auth');
    }

    public function resetPassword(): void
    {
        if (!CSRF::validate($_POST['_token'] ?? '')) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }
        $token = (string)($_POST['token'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $user = DB::fetch('SELECT * FROM users WHERE reset_token = :t AND reset_expires_at >= NOW() LIMIT 1', ['t' => $token]);
        if (!$user) {
            $this->render('auth/reset', ['error' => 'Invalid or expired token', 'token' => $token], 'auth');
            return;
        }
        DB::query('UPDATE users SET password_hash = :p, reset_token = NULL, reset_expires_at = NULL WHERE id = :id', [
            'p' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $user['id'],
        ]);
        $this->render('auth/login', ['success' => 'Password has been reset. Please login.'], 'auth');
    }
}