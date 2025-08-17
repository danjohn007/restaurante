<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            $this->connection = null;
            error_log("Database connection failed: " . $e->getMessage());
            
            // Don't kill the application, let controllers handle the error gracefully
            // In development, we might want to show setup instructions
            if (ini_get('display_errors')) {
                // Store error for potential display later, but don't die immediately
                $_SESSION['db_error'] = 'Database connection failed: ' . $e->getMessage() . '<br><br>
                     <strong>Setup Instructions:</strong><br>
                     1. Create MySQL database: <code>' . DB_NAME . '</code><br>
                     2. Import schema: <code>mysql -u root -p ' . DB_NAME . ' < database/schema.sql</code><br>
                     3. Update database credentials in <code>config/config.php</code><br>
                     4. For testing without MySQL, use SQLite (modify config)';
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function isConnected() {
        return $this->connection !== null;
    }

    public function prepare($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->prepare($sql);
    }

    public function query($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->query($sql);
    }

    public function lastInsertId() {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->lastInsertId();
    }

    public function beginTransaction() {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->beginTransaction();
    }

    public function commit() {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->commit();
    }

    public function rollback() {
        if (!$this->isConnected()) {
            throw new Exception("Database not connected");
        }
        return $this->connection->rollback();
    }
}
?>