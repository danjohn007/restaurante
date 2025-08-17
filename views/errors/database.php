<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-8 text-center">
            <div class="display-1 text-warning">⚠️</div>
            <h1 class="h3 mb-3">Error de Configuración de Base de Datos</h1>
            <div class="alert alert-warning text-left">
                <h5><i class="bi bi-exclamation-triangle"></i> El sistema no puede conectarse a la base de datos</h5>
                <p class="mb-2">Para resolver este problema, por favor:</p>
                <ol>
                    <li>Verifique que MySQL esté ejecutándose</li>
                    <li>Confirme que la base de datos <code><?php echo DB_NAME; ?></code> existe</li>
                    <li>Verifique las credenciales en <code>config/config.php</code>:
                        <ul>
                            <li>Host: <code><?php echo DB_HOST; ?></code></li>
                            <li>Usuario: <code><?php echo DB_USER; ?></code></li>
                            <li>Base de datos: <code><?php echo DB_NAME; ?></code></li>
                        </ul>
                    </li>
                    <li>Si es la primera instalación, importe el esquema: 
                        <br><code>mysql -u <?php echo DB_USER; ?> -p <?php echo DB_NAME; ?> &lt; database/schema.sql</code>
                    </li>
                </ol>
            </div>
            
            <div class="mt-4">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Intentar Nuevamente
                </a>
                
                <?php if (ini_get('display_errors')): ?>
                <div class="mt-3">
                    <a href="<?php echo BASE_URL; ?>test_routing.php" class="btn btn-outline-secondary">
                        <i class="bi bi-tools"></i> Ejecutar Diagnósticos
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-4 text-muted small">
                <p>Si el problema persiste, contacte al administrador del sistema.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>