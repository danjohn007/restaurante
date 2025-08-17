<?php include 'views/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
                <div class="text-muted">
                    <i class="bi bi-clock"></i> <?php echo date('d/m/Y H:i:s'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-75 small">Ventas Hoy</div>
                            <div class="h4"><?php echo format_currency($stats['today_sales']); ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-currency-dollar fs-1"></i>
                        </div>
                    </div>
                    <div class="small text-white-75">
                        <i class="bi bi-receipt"></i> <?php echo $stats['today_orders']; ?> pedidos
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-75 small">Pedidos Activos</div>
                            <div class="h4"><?php echo $stats['active_orders']; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                    <div class="small text-white-75">
                        <i class="bi bi-arrow-up"></i> En proceso
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-75 small">Mesas Disponibles</div>
                            <div class="h4"><?php echo $stats['available_tables']; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-table fs-1"></i>
                        </div>
                    </div>
                    <div class="small text-white-75">
                        <i class="bi bi-check-circle"></i> Libres
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-75 small">Stock Bajo</div>
                            <div class="h4"><?php echo $stats['low_stock_items']; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-triangle fs-1"></i>
                        </div>
                    </div>
                    <div class="small text-white-75">
                        <i class="bi bi-box-seam"></i> Productos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul"></i> Pedidos Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mesa</th>
                                    <th>Tipo</th>
                                    <th>Mesero</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td>
                                        <?php echo $order['table_number'] ? $order['table_number'] : '-'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $types = [
                                            'dine_in' => 'En sala',
                                            'takeout' => 'Para llevar',
                                            'delivery' => 'Delivery'
                                        ];
                                        echo $types[$order['order_type']] ?? $order['order_type'];
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['waiter_name']); ?></td>
                                    <td>
                                        <?php 
                                        $status_classes = [
                                            'pending' => 'badge bg-warning',
                                            'preparing' => 'badge bg-info',
                                            'ready' => 'badge bg-success',
                                            'served' => 'badge bg-primary',
                                            'paid' => 'badge bg-dark',
                                            'cancelled' => 'badge bg-danger'
                                        ];
                                        $status_labels = [
                                            'pending' => 'Pendiente',
                                            'preparing' => 'Preparando',
                                            'ready' => 'Listo',
                                            'served' => 'Servido',
                                            'paid' => 'Pagado',
                                            'cancelled' => 'Cancelado'
                                        ];
                                        ?>
                                        <span class="<?php echo $status_classes[$order['status']] ?? 'badge bg-secondary'; ?>">
                                            <?php echo $status_labels[$order['status']] ?? $order['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo format_currency($order['total']); ?></td>
                                    <td><?php echo format_date($order['created_at'], 'd/m H:i'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">No hay pedidos recientes</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Statistics and Quick Actions -->
        <div class="col-xl-4">
            <!-- Table Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart"></i> Estado de Mesas
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="tableStatusChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if (has_role(['waiter'])): ?>
                        <a href="<?php echo BASE_URL; ?>orders/create" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nuevo Pedido
                        </a>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager', 'waiter'])): ?>
                        <a href="<?php echo BASE_URL; ?>tables/layout" class="btn btn-info">
                            <i class="bi bi-layout-wtf"></i> Ver Layout
                        </a>
                        <?php endif; ?>
                        
                        <?php if (has_role(['admin', 'manager'])): ?>
                        <a href="<?php echo BASE_URL; ?>menu" class="btn btn-success">
                            <i class="bi bi-book"></i> Gestionar Menú
                        </a>
                        <a href="<?php echo BASE_URL; ?>inventory" class="btn btn-warning">
                            <i class="bi bi-box-seam"></i> Ver Inventario
                        </a>
                        <a href="<?php echo BASE_URL; ?>reports" class="btn btn-dark">
                            <i class="bi bi-graph-up"></i> Ver Reportes
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Summary -->
    <?php if (has_role(['admin', 'manager'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up"></i> Resumen de Ventas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h6 class="text-muted">Esta Semana</h6>
                            <h4 class="text-primary"><?php echo format_currency($stats['week_sales']); ?></h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Este Mes</h6>
                            <h4 class="text-success"><?php echo format_currency($stats['month_sales']); ?></h4>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-muted">Promedio Diario (Mes)</h6>
                            <h4 class="text-info"><?php echo format_currency($stats['month_sales'] / date('j')); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Table Status Chart
const ctx = document.getElementById('tableStatusChart').getContext('2d');
const tableStatusChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Disponibles', 'Ocupadas', 'Reservadas', 'Limpieza'],
        datasets: [{
            data: [
                <?php echo $table_status['available']; ?>,
                <?php echo $table_status['occupied']; ?>,
                <?php echo $table_status['reserved']; ?>,
                <?php echo $table_status['cleaning']; ?>
            ],
            backgroundColor: [
                '#198754',
                '#dc3545',
                '#ffc107',
                '#6c757d'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php include 'views/layouts/footer.php'; ?>