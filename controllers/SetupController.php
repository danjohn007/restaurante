<?php
/**
 * Setup Controller - handles initial setup and configuration issues
 * This controller works without database connection
 */
class SetupController {
    
    public function index() {
        // Check if database is properly configured
        if ($this->isDatabaseConfigured()) {
            // Database is working, redirect to dashboard
            header('Location: ' . BASE_URL . 'dashboard');
            exit();
        }
        
        // Show setup page
        $this->showSetupPage();
    }
    
    public function database() {
        $this->showSetupPage();
    }
    
    private function isDatabaseConfigured() {
        try {
            require_once 'config/database.php';
            $db = Database::getInstance();
            return $db->isConnected();
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function showSetupPage() {
        $error_message = $_SESSION['db_error'] ?? null;
        unset($_SESSION['db_error']);
        
        include 'views/setup/database_error.php';
    }
}
?>