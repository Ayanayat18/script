<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

class ServiceCategoriesController extends Controller
{
    public function index(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $cats = DB::fetchAll('SELECT * FROM service_categories ORDER BY sort_order, name');
        $this->render('admin/categories/index', ['title' => 'Service Categories', 'cats' => $cats], 'admin');
    }

    public function createForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $this->render('admin/categories/create', ['title' => 'Create Category'], 'admin');
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $name = trim($_POST['name'] ?? '');
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = (int)($_POST['status'] ?? 1);
        if ($name !== '') {
            DB::insert('INSERT INTO service_categories (name, status, sort_order, created_at, updated_at) VALUES (:n,:s,:o,NOW(),NOW())', [
                'n' => $name, 's' => $status, 'o' => $sort,
            ]);
        }
        $this->redirect('/admin/services');
    }

    public function editForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $id = (int)($_GET['id'] ?? 0);
        $cat = DB::fetch('SELECT * FROM service_categories WHERE id = :id', ['id' => $id]);
        $this->render('admin/categories/edit', ['title' => 'Edit Category', 'cat' => $cat], 'admin');
    }

    public function update(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $sort = (int)($_POST['sort_order'] ?? 0);
        $status = (int)($_POST['status'] ?? 1);
        if ($id > 0 && $name !== '') {
            DB::query('UPDATE service_categories SET name = :n, status = :s, sort_order = :o, updated_at = NOW() WHERE id = :id', [
                'n' => $name, 's' => $status, 'o' => $sort, 'id' => $id,
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
            DB::query('DELETE FROM service_categories WHERE id = :id', ['id' => $id]);
        }
        $this->redirect('/admin/services');
    }
}