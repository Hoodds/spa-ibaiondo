<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Panel de Control</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?= Helper::url('trabajador/dashboard') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="<?= Helper::url('trabajador/reservas') ?>" class="list-group-item list-group-item-action">
                        <i class="fas fa-calendar-alt me-2"></i> Mis Reservas
                    </a>
                    <a href="<?= Helper::url('trabajador/valoraciones') ?>" class="list-group-item list-group-item-action active">
                        <i class="fas fa-star me-2"></i> Mis Valoraciones
                    </a>
                    <a href="<?= Helper::url('trabajador/logout') ?>" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <h1 class="mb-4">Mis Valoraciones</h1>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> El sistema de valoraciones está en desarrollo y estará disponible próximamente.
        </div>
    </div>
</div>

