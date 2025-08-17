<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'restaurante_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('BASE_URL', '/restaurante/'); // Change this to your installation directory
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