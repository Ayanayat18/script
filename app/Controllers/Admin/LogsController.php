<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class LogsController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $logs = DB::fetchAll('SELECT * FROM cron_logs ORDER BY ran_at DESC, id DESC LIMIT 200');
        $this->render('admin/logs/index', [
            'title' => 'Logs',
            'logs' => $logs,
        ], 'admin');
    }
}