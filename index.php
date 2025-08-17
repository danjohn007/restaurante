<?php
require_once 'includes/functions.php';
require_once 'config/database.php';

/**
 * Simple MVC Router
 */
class Router {
    private $routes = [];
    private $default_controller = 'Dashboard';
    private $default_action = 'index';

    public function __construct() {
        $this->loadRoutes();
    }

    private function loadRoutes() {
        // Define routes
        $this->routes = [
            '' => ['controller' => 'Dashboard', 'action' => 'index'],
            'dashboard' => ['controller' => 'Dashboard', 'action' => 'index'],
            
            // Auth routes
            'login' => ['controller' => 'Auth', 'action' => 'login'],
            'logout' => ['controller' => 'Auth', 'action' => 'logout'],
            'register' => ['controller' => 'Auth', 'action' => 'register'],
            
            // User management
            'users' => ['controller' => 'User', 'action' => 'index'],
            'users/create' => ['controller' => 'User', 'action' => 'create'],
            'users/edit' => ['controller' => 'User', 'action' => 'edit'],
            'users/delete' => ['controller' => 'User', 'action' => 'delete'],
            
            // Table management
            'tables' => ['controller' => 'Table', 'action' => 'index'],
            'tables/create' => ['controller' => 'Table', 'action' => 'create'],
            'tables/edit' => ['controller' => 'Table', 'action' => 'edit'],
            'tables/layout' => ['controller' => 'Table', 'action' => 'layout'],
            
            // Menu management
            'menu' => ['controller' => 'Menu', 'action' => 'index'],
            'menu/categories' => ['controller' => 'Menu', 'action' => 'categories'],
            'menu/items' => ['controller' => 'Menu', 'action' => 'items'],
            'menu/create' => ['controller' => 'Menu', 'action' => 'create'],
            'menu/edit' => ['controller' => 'Menu', 'action' => 'edit'],
            
            // Order management
            'orders' => ['controller' => 'Order', 'action' => 'index'],
            'orders/create' => ['controller' => 'Order', 'action' => 'create'],
            'orders/view' => ['controller' => 'Order', 'action' => 'view'],
            'orders/kitchen' => ['controller' => 'Order', 'action' => 'kitchen'],
            
            // Inventory
            'inventory' => ['controller' => 'Inventory', 'action' => 'index'],
            'inventory/items' => ['controller' => 'Inventory', 'action' => 'items'],
            'inventory/movements' => ['controller' => 'Inventory', 'action' => 'movements'],
            
            // Reports
            'reports' => ['controller' => 'Report', 'action' => 'index'],
            'reports/sales' => ['controller' => 'Report', 'action' => 'sales'],
            'reports/inventory' => ['controller' => 'Report', 'action' => 'inventory'],
            
            // Reservations
            'reservations' => ['controller' => 'Reservation', 'action' => 'index'],
            'reservations/create' => ['controller' => 'Reservation', 'action' => 'create'],
            
            // Settings
            'settings' => ['controller' => 'Setting', 'action' => 'index']
        ];
    }

    public function route() {
        $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
        
        // Check if database is available before routing to controllers that need it
        if (!$this->isDatabaseAvailable()) {
            $this->showDatabaseSetup();
            return;
        }
        
        // Handle direct controller/action URLs
        if (isset($this->routes[$url])) {
            $controller = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
        } else {
            // Parse URL for dynamic routing
            $parts = explode('/', $url);
            $controller = !empty($parts[0]) ? ucfirst($parts[0]) : $this->default_controller;
            $action = !empty($parts[1]) ? $parts[1] : $this->default_action;
        }

        // Load controller
        $controller_file = "controllers/{$controller}Controller.php";
        
        if (file_exists($controller_file)) {
            require_once $controller_file;
            $controller_class = $controller . 'Controller';
            
            if (class_exists($controller_class)) {
                $controller_instance = new $controller_class();
                
                if (method_exists($controller_instance, $action)) {
                    $controller_instance->$action();
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
        } else {
            $this->show404();
        }
    }
    
    private function isDatabaseAvailable() {
        try {
            $db = Database::getInstance();
            return $db->isConnected();
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function showDatabaseSetup() {
        $error_message = 'Database connection failed. Please configure the database to continue.';
        include 'views/setup/database_error.php';
    }

    private function show404() {
        header("HTTP/1.0 404 Not Found");
        include 'views/errors/404.php';
    }
}

// Initialize router and handle request
try {
    $router = new Router();
    $router->route();
} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    
    // Show error page in production, show details in development
    if (ini_get('display_errors')) {
        die("Application Error: " . $e->getMessage());
    } else {
        include 'views/errors/500.php';
    }
}
?>