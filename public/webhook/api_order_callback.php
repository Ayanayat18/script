<?php
require dirname(__DIR__, 2) . '/app/bootstrap.php';

use App\Core\DB;

// Optional secret in query to reduce noise
if (isset($_GET['secret']) && $_GET['secret'] !== CRON_SECRET) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$payload = file_get_contents('php://input') ?: json_encode($_REQUEST);
DB::insert('INSERT INTO cron_logs (job, status, message, ran_at) VALUES (?,?,?,NOW())', ['webhook', 'ok', substr($payload, 0, 1900)]);
echo 'OK';