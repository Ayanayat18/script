<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

class ServicesCrudController extends Controller
{
    public function createForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $cats = DB::fetchAll('SELECT id,name FROM service_categories ORDER BY name');
        $this->render('admin/services/create', ['title' => 'Create Service', 'cats' => $cats], 'admin');
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $name = trim($_POST['name'] ?? '');
        $cat = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $desc = trim($_POST['description'] ?? '');
        $status = (int)($_POST['status'] ?? 1);
        if ($name && $cat) {
            DB::insert('INSERT INTO services (category_id, name, description, price, status, created_at, updated_at) VALUES (:c,:n,:d,:p,:s,NOW(),NOW())', [
                'c' => $cat, 'n' => $name, 'd' => $desc, 'p' => $price, 's' => $status,
            ]);
        }
        $this->redirect('/admin/services');
    }

    public function editForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $id = (int)($_GET['id'] ?? 0);
        $service = DB::fetch('SELECT * FROM services WHERE id = :id', ['id' => $id]);
        $cats = DB::fetchAll('SELECT id,name FROM service_categories ORDER BY name');
        $this->render('admin/services/edit', ['title' => 'Edit Service', 'service' => $service, 'cats' => $cats], 'admin');
    }

    public function update(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $cat = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $desc = trim($_POST['description'] ?? '');
        $status = (int)($_POST['status'] ?? 1);
        if ($id > 0 && $name && $cat) {
            DB::query('UPDATE services SET category_id=:c, name=:n, description=:d, price=:p, status=:s, updated_at=NOW() WHERE id=:id', [
                'c' => $cat, 'n' => $name, 'd' => $desc, 'p' => $price, 's' => $status, 'id' => $id,
            ]);
        }
        $this->redirect('/admin/services');
    }

    public function delete(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            DB::query('DELETE FROM services WHERE id = :id', ['id' => $id]);
        }
        $this->redirect('/admin/services');
    }
}