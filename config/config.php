<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_restaurante');
define('DB_USER', 'ejercito_restaurante');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// For development/demo purposes, we'll use SQLite as fallback
define('USE_SQLITE_FALLBACK', true);
define('SQLITE_DB_PATH', 'database/restaurante.db');

// Application configuration
define('BASE_URL', 'https://ejercitodigital.com.mx/restaurante/'); // Change this to your installation directory
define('SITE_NAME', 'Sistema de Restaurante');
define('SITE_DESCRIPTION', 'Sistema Online de Administración de Restaurante');

// Security settings
define('SECRET_KEY', 'your-secret-key-here-change-in-production');
define('SESSION_LIFETIME', 3600); // 1 hour

// Email configuration (for notifications)
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('FROM_EMAIL', 'noreply@restaurante.com');
define('FROM_NAME', 'Sistema Restaurante');

// File upload settings
define('UPLOAD_DIR', 'assets/images/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Timezone
date_default_timezone_set('America/Mexico_City');

// Error reporting (set to 0 in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
