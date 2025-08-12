<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class SettingsController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $settings = DB::fetchAll('SELECT `key`,`value` FROM settings ORDER BY `key`');
        $this->render('admin/settings/index', [
            'title' => 'Settings',
            'settings' => $settings,
        ], 'admin');
    }
}