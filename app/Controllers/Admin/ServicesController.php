<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class ServicesController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $categories = DB::fetchAll('SELECT * FROM service_categories ORDER BY sort_order, name');
        $services = DB::fetchAll('SELECT s.*, c.name AS category_name FROM services s JOIN service_categories c ON c.id = s.category_id ORDER BY c.sort_order, s.name');
        $this->render('admin/services/index', [
            'title' => 'Services',
            'categories' => $categories,
            'services' => $services,
        ], 'admin');
    }
}