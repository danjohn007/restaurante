<?php
session_start();
require_once 'config/config.php';

/**
 * Security functions
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Authentication functions
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'login');
        exit();
    }
}

function has_role($required_roles) {
    if (!is_logged_in()) {
        return false;
    }
    
    if (is_string($required_roles)) {
        $required_roles = [$required_roles];
    }
    
    return in_array($_SESSION['user_role'], $required_roles);
}

function require_role($required_roles) {
    require_login();
    if (!has_role($required_roles)) {
        header('HTTP/1.1 403 Forbidden');
        include 'views/errors/403.php';
        exit();
    }
}

function get_logged_user() {
    if (!is_logged_in()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'first_name' => $_SESSION['user_first_name'],
        'last_name' => $_SESSION['user_last_name'],
        'role' => $_SESSION['user_role']
    ];
}

/**
 * Utility functions
 */
function redirect($path = '') {
    $url = BASE_URL . ltrim($path, '/');
    header("Location: $url");
    exit();
}

function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function format_date($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

function get_setting($key, $default = null) {
    static $settings = null;
    
    if ($settings === null) {
        $settings = [];
        try {
            require_once 'config/database.php';
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings");
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // Handle database error gracefully
        }
    }
    
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Flash message functions
 */
function set_flash_message($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash_messages() {
    $messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

/**
 * File upload functions
 */
function upload_file($file, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($file['tmp_name']),
        [
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ],
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    if (!in_array($ext, $allowed_types)) {
        throw new RuntimeException('File type not allowed.');
    }

    $filename = sprintf('%s.%s', uniqid(), $ext);
    $upload_path = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    return $filename;
}

/**
 * Pagination helper
 */
function paginate($total_records, $records_per_page, $current_page = 1) {
    $total_pages = ceil($total_records / $records_per_page);
    $current_page = max(1, min($total_pages, $current_page));
    $offset = ($current_page - 1) * $records_per_page;
    
    return [
        'total_records' => $total_records,
        'records_per_page' => $records_per_page,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'has_previous' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}

/**
 * Debug helper
 */
function debug($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
?>