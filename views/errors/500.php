<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-6 text-center">
            <div class="display-1 text-warning">500</div>
            <h1 class="h3 mb-3">Error del Servidor</h1>
            <p class="text-muted mb-4">
                Ha ocurrido un error interno. Por favor, inténtalo de nuevo más tarde.
            </p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise"></i> Intentar de nuevo
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>