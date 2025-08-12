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
$router->get('/admin/categories', 'Admin\\ServiceCategoriesController@index');
$router->get('/admin/categories/create', 'Admin\\ServiceCategoriesController@createForm');
$router->post('/admin/categories/create', 'Admin\\ServiceCategoriesController@create');
$router->get('/admin/categories/edit', 'Admin\\ServiceCategoriesController@editForm');
$router->post('/admin/categories/update', 'Admin\\ServiceCategoriesController@update');
$router->post('/admin/categories/delete', 'Admin\\ServiceCategoriesController@delete');
$router->get('/admin/services/create', 'Admin\\ServicesCrudController@createForm');
$router->post('/admin/services/create', 'Admin\\ServicesCrudController@create');
$router->get('/admin/services/edit', 'Admin\\ServicesCrudController@editForm');
$router->post('/admin/services/update', 'Admin\\ServicesCrudController@update');
$router->post('/admin/services/delete', 'Admin\\ServicesCrudController@delete');
$router->get('/admin/apis', 'Admin\\ApisController@index');
$router->get('/admin/apis/create', 'Admin\\ApisController@createForm');
$router->post('/admin/apis/create', 'Admin\\ApisController@create');
$router->get('/admin/orders', 'Admin\\OrdersController@index');
$router->get('/admin/wallet', 'Admin\\WalletController@index');
$router->get('/admin/reports', 'Admin\\ReportsController@index');
$router->get('/admin/settings', 'Admin\\SettingsController@index');
$router->post('/admin/settings/save', 'Admin\\SettingsController@save');
$router->get('/admin/logs', 'Admin\\LogsController@index');

// User
$router->get('/dashboard', 'User\\DashboardController@index');
$router->get('/orders', 'User\\OrdersController@index');
$router->get('/wallet', 'User\\WalletController@index');
$router->post('/wallet/add', 'User\\WalletController@addFunds');
$router->get('/wallet/callback', 'User\\WalletController@callback');
$router->get('/services', 'User\\ServicesController@index');
$router->get('/place-order', 'User\\PlaceOrderController@form');
$router->post('/place-order', 'User\\PlaceOrderController@submit');
$router->get('/notifications', 'User\\NotificationsController@index');
$router->get('/profile', 'User\\ProfileController@index');
$router->get('/subscriptions', 'User\\SubscriptionsController@index');
$router->get('/support', 'User\\SupportController@index');
$router->post('/support/send', 'User\\SupportController@send');

// Admin extra actions
$router->get('/admin/wallet/adjust', 'Admin\\WalletController@adjustForm');
$router->post('/admin/wallet/adjust', 'Admin\\WalletController@adjust');
$router->get('/admin/users/subscription', 'Admin\\UsersController@subscriptionForm');
$router->post('/admin/users/subscription', 'Admin\\UsersController@updateSubscription');
$router->get('/admin/services/map', 'Admin\\ServicesController@mapForm');
$router->post('/admin/services/map', 'Admin\\ServicesController@mapSave');
$router->post('/admin/services/sync-prices', 'Admin\\ServicesController@syncPrices');