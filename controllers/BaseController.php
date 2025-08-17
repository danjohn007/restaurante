<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

/**
 * Base controller class
 */
abstract class BaseController {
    protected $db;
    protected $user;

    public function __construct() {
        try {
            $this->db = Database::getInstance();
            $this->user = get_logged_user();
        } catch (Exception $e) {
            // Log the database error
            error_log("Database connection failed in BaseController: " . $e->getMessage());
            
            // Set db to null so controllers can check if database is available
            $this->db = null;
            $this->user = null;
            
            // Don't throw the exception here, let controllers handle it
        }
    }

    /**
     * Load a view with data
     */
    protected function view($view, $data = []) {
        // Extract data array to variables
        extract($data);
        
        // Include the view file
        $view_file = "views/{$view}.php";
        if (file_exists($view_file)) {
            include $view_file;
        } else {
            throw new Exception("View not found: {$view}");
        }
    }

    /**
     * Load a model
     */
    protected function model($model) {
        $model_file = "models/{$model}.php";
        if (file_exists($model_file)) {
            require_once $model_file;
            return new $model();
        } else {
            throw new Exception("Model not found: {$model}");
        }
    }

    /**
     * JSON response
     */
    protected function json($data, $status_code = 200) {
        header('Content-Type: application/json');
        http_response_code($status_code);
        echo json_encode($data);
        exit();
    }

    /**
     * Redirect helper
     */
    protected function redirect($path = '') {
        redirect($path);
    }

    /**
     * Validate CSRF token
     */
    protected function validateCSRF() {
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            throw new Exception('Invalid CSRF token');
        }
    }

    /**
     * Check if database is available
     */
    protected function isDatabaseAvailable() {
        return $this->db !== null;
    }

    /**
     * Show database error page
     */
    protected function showDatabaseError() {
        $this->view('errors/database', [
            'title' => 'Error de Configuración'
        ]);
    }

    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isDatabaseAvailable()) {
            $this->showDatabaseError();
            return false;
        }
        require_login();
        return true;
    }

    /**
     * Require specific role
     */
    protected function requireRole($roles) {
        require_role($roles);
    }
}
?>