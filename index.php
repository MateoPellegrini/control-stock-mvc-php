<?php
session_start();

// index.php - Front controller
require __DIR__ . '/config/config.php';
require __DIR__ . '/core/Database.php';
require __DIR__ . '/core/Controller.php';

require __DIR__ . '/models/User.php';
require __DIR__ . '/models/Category.php';
require __DIR__ . '/models/Product.php';
require __DIR__ . '/models/StockMovement.php';
require __DIR__ . '/models/Purchase.php';
require __DIR__ . '/models/Sale.php';


require __DIR__ . '/controllers/AuthController.php';
require __DIR__ . '/controllers/ProductController.php';
require __DIR__ . '/controllers/PurchaseController.php';
require __DIR__ . '/controllers/SaleController.php';


$controllerName = $_GET['controller'] ?? 'auth';
$actionName     = $_GET['action'] ?? 'login';

switch ($controllerName) {
    case 'products':
        $controller = new ProductController();
        break;
    case 'purchases':
        $controller = new PurchaseController();
        break;
    case 'sales':
        $controller = new SaleController();
        break;
    case 'auth':
    default:
        $controller = new AuthController();
        break;
}

if (!method_exists($controller, $actionName)) {
    http_response_code(404);
    echo "404 - Acción no encontrada";
    exit;
}

$controller->{$actionName}();
