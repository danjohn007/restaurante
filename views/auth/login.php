<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shop display-4 text-primary"></i>
                        <h3 class="mt-3"><?php echo SITE_NAME; ?></h3>
                        <p class="text-muted">Iniciar Sesión</p>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>login">
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
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>"
                                       required 
                                       autocomplete="email">
                            </div>
                        </div>

                        <div class="mb-3">
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
                                       autocomplete="current-password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                    </form>

                    <div class="text-center">
                        <small class="text-muted">
                            Credenciales por defecto:<br>
                            <strong>admin@restaurante.com</strong> / <strong>password123</strong>
                        </small>
                    </div>
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

.form-control {
    border-left: none;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
}

.input-group .form-control:focus {
    border-left: none;
}
</style>

<?php include 'views/layouts/footer.php'; ?>