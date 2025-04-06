<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Usuarios</h6>
                        <h2 class="mb-0"><?= $totalUsuarios ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="<?= Helper::url('admin/usuarios') ?>" class="text-white text-decoration-none">Ver detalles</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Trabajadores</h6>
                        <h2 class="mb-0"><?= $totalTrabajadores ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-user-tie fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="<?= Helper::url('admin/trabajadores') ?>" class="text-white text-decoration-none">Ver detalles</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Servicios</h6>
                        <h2 class="mb-0"><?= $totalServicios ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-concierge-bell fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="<?= Helper::url('admin/servicios') ?>" class="text-dark text-decoration-none">Ver detalles</a>
                <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Reservas</h6>
                        <h2 class="mb-0"><?= $totalReservas ?></h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a href="<?= Helper::url('admin/reservas') ?>" class="text-white text-decoration-none">Ver detalles</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Reservas por Mes
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Los gráficos estadísticos estarán disponibles próximamente.
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-pie me-1"></i>
                Servicios Más Populares
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Los gráficos estadísticos estarán disponibles próximamente.
                </div>
            </div>
        </div>
    </div>
</div>

