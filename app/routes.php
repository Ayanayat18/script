<?php
use App\Core\Router;

/** @var Router $router */

$router->get('/', 'HomeController@index');

// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot', 'AuthController@forgotForm');
$router->post('/forgot', 'AuthController@sendReset');
$router->get('/reset', 'AuthController@resetForm');
$router->post('/reset', 'AuthController@resetPassword');

// Admin
$router->get('/admin', 'Admin\\DashboardController@index');
$router->get('/admin/users', 'Admin\\UsersController@index');
$router->get('/admin/services', 'Admin\\ServicesController@index');
$router->get('/admin/apis', 'Admin\\ApisController@index');
$router->get('/admin/orders', 'Admin\\OrdersController@index');
$router->get('/admin/wallet', 'Admin\\WalletController@index');
$router->get('/admin/reports', 'Admin\\ReportsController@index');
$router->get('/admin/settings', 'Admin\\SettingsController@index');
$router->get('/admin/logs', 'Admin\\LogsController@index');

// User
$router->get('/dashboard', 'User\\DashboardController@index');
$router->get('/orders', 'User\\OrdersController@index');
$router->get('/wallet', 'User\\WalletController@index');
$router->post('/wallet/add', 'User\\WalletController@addFunds');
$router->get('/services', 'User\\ServicesController@index');
$router->get('/profile', 'User\\ProfileController@index');
$router->get('/subscriptions', 'User\\SubscriptionsController@index');
$router->get('/support', 'User\\SupportController@index');
$router->post('/support/send', 'User\\SupportController@send');