<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;
use App\Core\CSRF;

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

    public function mapForm(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        $apis = DB::fetchAll('SELECT * FROM apis WHERE status = 1 ORDER BY name');
        $apiServices = DB::fetchAll('SELECT * FROM api_services ORDER BY api_id, name');
        $services = DB::fetchAll('SELECT id, name FROM services ORDER BY name');
        $this->render('admin/services/map', [
            'title' => 'Map API Services',
            'apis' => $apis,
            'apiServices' => $apiServices,
            'services' => $services,
        ], 'admin');
    }

    public function mapSave(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $pairs = $_POST['map'] ?? [];
        foreach ($pairs as $apiServiceId => $serviceId) {
            $apiServiceId = (int)$apiServiceId; $serviceId = (int)$serviceId;
            if ($apiServiceId > 0 && $serviceId > 0) {
                DB::query('UPDATE services SET api_service_id = :asid WHERE id = :sid', ['asid' => $apiServiceId, 'sid' => $serviceId]);
            }
        }
        $this->redirect('/admin/services');
    }

    public function syncPrices(): void
    {
        $this->requireRole(['admin', 'super_admin']);
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        // For each mapped service, pull price from api_services
        $mapped = DB::fetchAll('SELECT s.id, a.price FROM services s JOIN api_services a ON a.id = s.api_service_id');
        foreach ($mapped as $m) {
            DB::query('UPDATE services SET price = :p WHERE id = :id', ['p' => $m['price'], 'id' => $m['id']]);
        }
        $this->redirect('/admin/services');
    }
}