<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-6 text-center">
            <div class="display-1 text-danger">403</div>
            <h1 class="h3 mb-3">Acceso Denegado</h1>
            <p class="text-muted mb-4">
                No tienes permisos para acceder a esta página.
            </p>
            <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-primary">
                <i class="bi bi-speedometer2"></i> Ir al Dashboard
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>