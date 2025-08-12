<?php
namespace App\Controllers\User;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\DB;
use App\Core\CSRF;
use App\Core\View;

class PlaceOrderController extends Controller
{
    public function form(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        $user = DB::fetch('SELECT price_markup_percent FROM users WHERE id = :id', ['id' => Auth::id()]);
        $markup = (float)($user['price_markup_percent'] ?? 0);
        $services = DB::fetchAll('SELECT s.*, c.name AS category_name FROM services s JOIN service_categories c ON c.id = s.category_id WHERE s.status = 1 ORDER BY c.sort_order, s.name');
        foreach ($services as &$s) {
            $s['final_price'] = round((float)$s['price'] * (1 + $markup / 100), 2);
        }
        $this->render('user/orders/place.php', [
            'title' => 'Place Order',
            'services' => $services,
        ], 'user');
    }

    public function submit(): void
    {
        if (!Auth::check()) { $this->redirect('/login'); }
        if (!CSRF::validate($_POST['_token'] ?? '')) { http_response_code(419); echo 'Invalid token'; return; }
        $serviceId = (int)($_POST['service_id'] ?? 0);
        $input = trim($_POST['input'] ?? '');
        $service = DB::fetch('SELECT * FROM services WHERE id = :id AND status = 1', ['id' => $serviceId]);
        if (!$service) { $this->redirect('/place-order'); }
        $user = DB::fetch('SELECT wallet_balance, price_markup_percent FROM users WHERE id = :id FOR UPDATE', ['id' => Auth::id()]);
        $price = (float)$service['price'] * (1 + (float)$user['price_markup_percent'] / 100);
        $price = round($price, 2);
        $minBalance = (float) \App\Core\Settings::get('min_balance', '0');
        if ((float)$user['wallet_balance'] < max($minBalance, $price)) {
            $this->render('user/orders/place.php', ['error' => 'Insufficient balance (minimum required: $' . number_format(max($minBalance,$price),2) . ')'], 'user');
            return;
        }
        $pdo = DB::pdo();
        $pdo->beginTransaction();
        try {
            $wallet = (float)$user['wallet_balance'];
            if ($wallet < $price) {
                $pdo->rollBack();
                $this->render('user/orders/place.php', ['error' => 'Insufficient balance'], 'user');
                return;
            }
            DB::query('UPDATE users SET wallet_balance = wallet_balance - :amt WHERE id = :id', ['amt' => $price, 'id' => Auth::id()]);
            DB::insert('INSERT INTO wallet_transactions (user_id, type, method, amount, reference, created_at) VALUES (:uid,\'debit\',:m,:amt,:ref,NOW())', [
                'uid' => Auth::id(), 'm' => 'order', 'amt' => $price, 'ref' => 'ORDER',
            ]);
            $orderId = DB::insert('INSERT INTO orders (user_id, service_id, status, input_data, price, created_at, updated_at) VALUES (:uid,:sid,\'pending\',:inp,:price,NOW(),NOW())', [
                'uid' => Auth::id(), 'sid' => $serviceId, 'inp' => json_encode(['input' => $input]), 'price' => $price,
            ]);
            // Attempt API submission (non-blocking best-effort)
            try { \App\Services\OrderProcessor::submitToApi($orderId); } catch (\Throwable $e) { /* ignore */ }
            $pdo->commit();
            \App\Core\Notifier::notify(Auth::id(), 'Order Placed', 'Your order #' . $orderId . ' has been created.');
            $this->redirect('/orders');
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) { $pdo->rollBack(); }
            http_response_code(500);
            echo View::e($e->getMessage());
        }
    }
}