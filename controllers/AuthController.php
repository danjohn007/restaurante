<?php
require_once 'BaseController.php';

class AuthController extends BaseController {

    public function login() {
        // If already logged in, redirect to dashboard
        if (is_logged_in()) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->view('auth/login', [
                'title' => 'Iniciar Sesión'
            ]);
        }
    }

    private function handleLogin() {
        try {
            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                throw new Exception('Email y contraseña son requeridos');
            }

            $user = $this->authenticateUser($email, $password);
            
            if ($user) {
                $this->setUserSession($user);
                set_flash_message('success', 'Bienvenido al sistema');
                $this->redirect('dashboard');
            } else {
                throw new Exception('Credenciales inválidas');
            }

        } catch (Exception $e) {
            set_flash_message('error', $e->getMessage());
            $this->view('auth/login', [
                'title' => 'Iniciar Sesión',
                'email' => $email ?? ''
            ]);
        }
    }

    private function authenticateUser($email, $password) {
        $stmt = $this->db->prepare(
            "SELECT id, email, password, first_name, last_name, role, is_active 
             FROM users 
             WHERE email = ? AND is_active = 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    private function setUserSession($user) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_first_name'] = $user['first_name'];
        $_SESSION['user_last_name'] = $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        // Start a new session for flash messages
        session_start();
        set_flash_message('info', 'Has cerrado sesión exitosamente');
        
        $this->redirect('login');
    }

    public function register() {
        // Only allow registration if user is admin or if no users exist
        $user_count = $this->getUserCount();
        
        if ($user_count > 0 && !has_role('admin')) {
            set_flash_message('error', 'No tienes permisos para registrar usuarios');
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
        } else {
            $this->view('auth/register', [
                'title' => 'Registrar Usuario'
            ]);
        }
    }

    private function handleRegister() {
        try {
            $this->validateCSRF();

            $email = sanitize_input($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $first_name = sanitize_input($_POST['first_name'] ?? '');
            $last_name = sanitize_input($_POST['last_name'] ?? '');
            $role = $_POST['role'] ?? 'waiter';
            $phone = sanitize_input($_POST['phone'] ?? '');

            // Validation
            if (empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
                throw new Exception('Todos los campos obligatorios deben ser completados');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Formato de email inválido');
            }

            if (strlen($password) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }

            if ($password !== $confirm_password) {
                throw new Exception('Las contraseñas no coinciden');
            }

            if ($this->emailExists($email)) {
                throw new Exception('El email ya está registrado');
            }

            $valid_roles = ['admin', 'manager', 'waiter', 'cashier', 'chef'];
            if (!in_array($role, $valid_roles)) {
                throw new Exception('Rol inválido');
            }

            // Create user
            $user_id = $this->createUser($email, $password, $first_name, $last_name, $role, $phone);
            
            if ($user_id) {
                set_flash_message('success', 'Usuario registrado exitosamente');
                
                // If this is the first user, log them in automatically
                if ($this->getUserCount() === 1) {
                    $user = $this->getUserById($user_id);
                    $this->setUserSession($user);
                    $this->redirect('dashboard');
                } else {
                    $this->redirect('users');
                }
            } else {
                throw new Exception('Error al crear el usuario');
            }

        } catch (Exception $e) {
            set_flash_message('error', $e->getMessage());
            $this->view('auth/register', [
                'title' => 'Registrar Usuario',
                'form_data' => $_POST
            ]);
        }
    }

    private function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    private function createUser($email, $password, $first_name, $last_name, $role, $phone) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare(
            "INSERT INTO users (email, password, first_name, last_name, role, phone) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        
        if ($stmt->execute([$email, $hashed_password, $first_name, $last_name, $role, $phone])) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    private function getUserCount() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE is_active = 1");
        return $stmt->fetchColumn();
    }

    private function getUserById($id) {
        $stmt = $this->db->prepare(
            "SELECT id, email, first_name, last_name, role 
             FROM users 
             WHERE id = ? AND is_active = 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>