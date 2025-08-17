<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php if (is_logged_in()): ?>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                    <i class="bi bi-shop"></i> <?php echo SITE_NAME; ?>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        
                        <?php if (has_role(['admin', 'manager', 'waiter'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-table"></i> Mesas
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>tables">Ver Mesas</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>tables/layout">Layout del Salón</a></li>
                                <?php if (has_role(['admin', 'manager'])): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>tables/create">Agregar Mesa</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager', 'waiter', 'chef'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-receipt"></i> Pedidos
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>orders">Todos los Pedidos</a></li>
                                <?php if (has_role(['waiter'])): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>orders/create">Nuevo Pedido</a></li>
                                <?php endif; ?>
                                <?php if (has_role(['chef'])): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>orders/kitchen">Cocina</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-book"></i> Menú
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>menu">Ver Menú</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>menu/categories">Categorías</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>menu/items">Platillos</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-box-seam"></i> Inventario
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>inventory">Resumen</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>inventory/items">Productos</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>inventory/movements">Movimientos</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-graph-up"></i> Reportes
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reports">Dashboard</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reports/sales">Ventas</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>reports/inventory">Inventario</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager', 'waiter'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>reservations">
                                <i class="bi bi-calendar-check"></i> Reservaciones
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <?php if (has_role(['admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>users">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>settings">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> 
                                <?php echo $_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            <?php 
                                            $roles = [
                                                'admin' => 'Administrador',
                                                'manager' => 'Gerente',
                                                'waiter' => 'Mesero',
                                                'cashier' => 'Cajero',
                                                'chef' => 'Chef'
                                            ];
                                            echo $roles[$_SESSION['user_role']] ?? $_SESSION['user_role'];
                                            ?>
                                        </small>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Flash Messages -->
    <?php 
    $flash_messages = get_flash_messages();
    if (!empty($flash_messages)): 
    ?>
    <div class="container-fluid mt-3">
        <?php foreach ($flash_messages as $message): ?>
        <div class="alert alert-<?php echo $message['type'] === 'error' ? 'danger' : $message['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Main Content -->