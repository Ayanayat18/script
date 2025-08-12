<?php
declare(strict_types=1);

// Bootstrap shared app context
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\App;
use App\Core\Router;

$router = new Router();
require BASE_PATH . '/app/routes.php';

$app = new App($router);
$app->run();