<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

class ApisController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $apis = DB::fetchAll('SELECT * FROM apis ORDER BY name');
        $this->render('admin/apis/index', [
            'title' => 'APIs',
            'apis' => $apis,
        ], 'admin');
    }

    public function createForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $this->render('admin/apis/create', ['title' => 'Add API'], 'admin');
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $name = trim($_POST['name'] ?? '');
        $base = trim($_POST['base_url'] ?? '');
        $key = trim($_POST['api_key'] ?? '');
        $type = trim($_POST['type'] ?? 'generic');
        if ($name && $base && $key) {
            DB::insert('INSERT INTO apis (name, base_url, api_key, type, status, created_at, updated_at) VALUES (:n,:b,:k,:t,1,NOW(),NOW())', [
                'n' => $name, 'b' => $base, 'k' => $key, 't' => $type,
            ]);
        }
        $this->redirect('/admin/apis');
    }
}