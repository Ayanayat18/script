<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;
use App\Core\Settings;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $settings = DB::fetchAll('SELECT `key`,`value` FROM settings ORDER BY `key`');
        $this->render('admin/settings/index', [
            'title' => 'Settings',
            'settings' => $settings,
            'min_balance' => Settings::get('min_balance', '0'),
            'dhru_services_path' => Settings::get('dhru_services_path', '/services'),
            'dhru_place_order_path' => Settings::get('dhru_place_order_path', '/orders'),
            'dhru_order_status_path' => Settings::get('dhru_order_status_path', '/orders/{id}'),
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        Settings::set('min_balance', (string)($_POST['min_balance'] ?? '0'));
        Settings::set('dhru_services_path', trim((string)($_POST['dhru_services_path'] ?? '/services')));
        Settings::set('dhru_place_order_path', trim((string)($_POST['dhru_place_order_path'] ?? '/orders')));
        Settings::set('dhru_order_status_path', trim((string)($_POST['dhru_order_status_path'] ?? '/orders/{id}')));
        $this->redirect('/admin/settings');
    }
}