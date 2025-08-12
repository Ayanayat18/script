<?php
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\DB;
use App\Core\Mailer;

if (php_sapi_name() !== 'cli') {
    if (!isset($_GET['secret']) || $_GET['secret'] !== CRON_SECRET) {
        http_response_code(403);
        exit('Forbidden');
    }
}

try {
    $errors = DB::fetchAll("SELECT * FROM cron_logs WHERE status = 'error' AND ran_at >= NOW() - INTERVAL 2 HOUR");
    if ($errors) {
        $body = '<h4>Cron Failures</h4><ul>';
        foreach ($errors as $e) {
            $body .= '<li><strong>' . htmlspecialchars($e['job']) . '</strong>: ' . htmlspecialchars($e['message'] ?? '') . ' at ' . htmlspecialchars($e['ran_at']) . '</li>';
        }
        $body .= '</ul>';
        Mailer::send(DEFAULT_ADMIN_EMAIL, SITE_NAME . ' - Cron Failures', $body);
    }
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['failed-jobs-alert', 'ok', 'Alerts processed']);
    echo "Failed jobs alert: done\n";
} catch (Throwable $e) {
    DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['failed-jobs-alert', 'error', $e->getMessage()]);
    echo "Error: " . $e->getMessage() . "\n";
}