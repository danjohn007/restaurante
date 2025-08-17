<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus display-4 text-success"></i>
                        <h3 class="mt-3">Registrar Usuario</h3>
                        <p class="text-muted">Crear nueva cuenta de usuario</p>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>register">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Nombre</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Apellido</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="tel" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone" 
                                       value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Seleccionar rol</option>
                                <option value="admin" <?php echo (($form_data['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Administrador</option>
                                <option value="manager" <?php echo (($form_data['role'] ?? '') === 'manager') ? 'selected' : ''; ?>>Gerente</option>
                                <option value="waiter" <?php echo (($form_data['role'] ?? '') === 'waiter') ? 'selected' : ''; ?>>Mesero</option>
                                <option value="cashier" <?php echo (($form_data['role'] ?? '') === 'cashier') ? 'selected' : ''; ?>>Cajero</option>
                                <option value="chef" <?php echo (($form_data['role'] ?? '') === 'chef') ? 'selected' : ''; ?>>Chef</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required
                                           minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           required
                                           minlength="6">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus"></i> Registrar Usuario
                            </button>
                            <?php if (is_logged_in()): ?>
                            <a href="<?php echo BASE_URL; ?>users" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Volver a Usuarios
                            </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.card {
    border: none;
    border-radius: 15px;
}

.display-4 {
    font-size: 3rem;
}

.input-group-text {
    background-color: #f8f9fa;
    border-right: none;
}

.form-control, .form-select {
    border-left: none;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
}

.input-group .form-control:focus, .input-group .form-select:focus {
    border-left: none;
}
</style>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Las contraseñas no coinciden');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    const password = this.value;
    
    if (confirmPassword.value && password !== confirmPassword.value) {
        confirmPassword.setCustomValidity('Las contraseñas no coinciden');
    } else {
        confirmPassword.setCustomValidity('');
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>