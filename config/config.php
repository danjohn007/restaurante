<?php
/**
 * Configuration file for Restaurant Management System
 * Contains database and application settings
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_restaurante');
define('DB_USER', 'ejercito_restaurante');
define('DB_PASS', 'Danjohn007!');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/');
define('SITE_URL', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . BASE_URL);

?>