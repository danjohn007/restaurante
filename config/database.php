<?php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Try MySQL first
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
            // Fallback to SQLite if MySQL fails and fallback is enabled
            if (defined('USE_SQLITE_FALLBACK') && USE_SQLITE_FALLBACK) {
                try {
                    $sqlite_path = SQLITE_DB_PATH;
                    $this->connection = new PDO(
                        "sqlite:" . $sqlite_path,
                        null,
                        null,
                        [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        ]
                    );
                    
                    // Initialize SQLite database if it doesn't exist
                    $this->initializeSQLiteDB();
                    
                } catch (PDOException $sqlite_e) {
                    // If SQLite also fails, show the original MySQL error with instructions
                    if (ini_get('display_errors')) {
                        die('Database connection failed: ' . $e->getMessage() . '<br><br>
                             <strong>Setup Instructions:</strong><br>
                             1. Create MySQL database: <code>' . DB_NAME . '</code><br>
                             2. Import schema: <code>mysql -u root -p ' . DB_NAME . ' < database/schema.sql</code><br>
                             3. Update database credentials in <code>config/config.php</code><br>
                             4. For testing without MySQL, use SQLite (modify config)');
                    } else {
                        die('Database connection failed. Please check configuration.');
                    }
                }
            } else {
                // If fallback is disabled, show MySQL error
                if (ini_get('display_errors')) {
                    die('Database connection failed: ' . $e->getMessage() . '<br><br>
                         <strong>Setup Instructions:</strong><br>
                         1. Create MySQL database: <code>' . DB_NAME . '</code><br>
                         2. Import schema: <code>mysql -u root -p ' . DB_NAME . ' < database/schema.sql</code><br>
                         3. Update database credentials in <code>config/config.php</code><br>
                         4. For testing without MySQL, use SQLite (modify config)');
                } else {
                    die('Database connection failed. Please check configuration.');
                }
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

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollback();
    }

    /**
     * Initialize SQLite database with essential tables and data
     */
    private function initializeSQLiteDB() {
        // Check if users table exists
        $result = $this->connection->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if ($result->fetch() === false) {
            // Create essential tables for demo
            $this->connection->exec("
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    email VARCHAR(255) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    role VARCHAR(20) NOT NULL DEFAULT 'waiter',
                    phone VARCHAR(20),
                    is_active BOOLEAN DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");

            // Insert default admin user (password: password123)
            $this->connection->exec("
                INSERT INTO users (email, password, first_name, last_name, role) VALUES
                ('admin@restaurante.com', '\$2y\$10\$tGuzk4VxhDxo.zu1OcwrROudjmXNQGxoOJefR8XdY4.DjVfNgcA9a', 'Admin', 'Sistema', 'admin')
            ");

            // Create basic tables for dashboard to work
            $this->connection->exec("
                CREATE TABLE orders (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    order_type VARCHAR(20) DEFAULT 'dine_in',
                    status VARCHAR(20) DEFAULT 'pending',
                    total DECIMAL(10,2) DEFAULT 0,
                    table_id INTEGER,
                    waiter_id INTEGER,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE restaurant_tables (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    table_number VARCHAR(10) UNIQUE NOT NULL,
                    capacity INTEGER NOT NULL DEFAULT 4,
                    status VARCHAR(20) DEFAULT 'available',
                    is_active BOOLEAN DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );

                CREATE TABLE inventory_items (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(100) NOT NULL,
                    current_stock DECIMAL(10,2) DEFAULT 0,
                    min_stock DECIMAL(10,2) DEFAULT 0,
                    is_active BOOLEAN DEFAULT 1
                );

                CREATE TABLE settings (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    setting_key VARCHAR(100) UNIQUE NOT NULL,
                    setting_value TEXT,
                    description TEXT
                );
            ");

            // Insert sample data
            $this->connection->exec("
                INSERT INTO restaurant_tables (table_number, capacity) VALUES
                ('M1', 4), ('M2', 4), ('M3', 2), ('M4', 6);

                INSERT INTO inventory_items (name, current_stock, min_stock) VALUES
                ('Producto Demo', 10, 5);

                INSERT INTO settings (setting_key, setting_value, description) VALUES
                ('restaurant_name', 'Mi Restaurante', 'Nombre del restaurante'),
                ('tax_rate', '16', 'Porcentaje de IVA');
            ");
        }
    }
}
?>