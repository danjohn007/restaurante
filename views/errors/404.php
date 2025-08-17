<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row min-vh-100 justify-content-center align-items-center">
        <div class="col-md-6 text-center">
            <div class="display-1 text-muted">404</div>
            <h1 class="h3 mb-3">Página no encontrada</h1>
            <p class="text-muted mb-4">
                La página que buscas no existe o ha sido movida.
            </p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                <i class="bi bi-house"></i> Volver al inicio
            </a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>