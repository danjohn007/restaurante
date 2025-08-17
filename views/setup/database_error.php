<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Requerida - <?php echo SITE_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row min-vh-100 justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h3 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Configuración de Base de Datos Requerida
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <?php echo $error_message ?? 'Error de conexión a la base de datos.'; ?>
                        </div>
                        
                        <h5>Pasos para configurar la base de datos:</h5>
                        <ol class="list-group list-group-numbered mb-4">
                            <li class="list-group-item">
                                <strong>Crear la base de datos MySQL:</strong>
                                <code class="d-block mt-2 p-2 bg-light">
                                    mysql -u root -p<br>
                                    CREATE DATABASE <?php echo DB_NAME; ?> CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;<br>
                                    exit;
                                </code>
                            </li>
                            <li class="list-group-item">
                                <strong>Importar el esquema de la base de datos:</strong>
                                <code class="d-block mt-2 p-2 bg-light">
                                    mysql -u <?php echo DB_USER; ?> -p <?php echo DB_NAME; ?> &lt; database/schema.sql
                                </code>
                            </li>
                            <li class="list-group-item">
                                <strong>Verificar las credenciales en <code>config/config.php</code>:</strong>
                                <ul class="mt-2">
                                    <li>DB_HOST: <?php echo DB_HOST; ?></li>
                                    <li>DB_NAME: <?php echo DB_NAME; ?></li>
                                    <li>DB_USER: <?php echo DB_USER; ?></li>
                                    <li>DB_PASS: [configurado]</li>
                                </ul>
                            </li>
                            <li class="list-group-item">
                                <strong>Verificar que el servidor MySQL esté ejecutándose:</strong>
                                <code class="d-block mt-2 p-2 bg-light">
                                    sudo systemctl status mysql<br>
                                    # o<br>
                                    sudo service mysql status
                                </code>
                            </li>
                        </ol>
                        
                        <div class="alert alert-info">
                            <strong><i class="bi bi-info-circle"></i> Nota:</strong>
                            Una vez configurada la base de datos correctamente, actualiza esta página para continuar.
                        </div>
                        
                        <div class="text-center">
                            <button onclick="location.reload()" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Intentar Nuevamente
                            </button>
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-secondary">
                                <i class="bi bi-house"></i> Ir al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>