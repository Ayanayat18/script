<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

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
}