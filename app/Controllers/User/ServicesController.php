<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Core\CSRF;

class ServicesController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $services = DB::fetchAll('SELECT s.*, c.name AS category_name FROM services s JOIN service_categories c ON c.id = s.category_id WHERE s.status = 1 ORDER BY c.sort_order, s.name');
        $this->render('user/services/index', [
            'title' => 'Services',
            'services' => $services,
        ], 'user');
    }
}