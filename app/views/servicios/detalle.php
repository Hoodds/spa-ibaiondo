<div class="row">
    <div class="col-md-8">
        <h1 class="mb-3"><?= Helper::e($servicio['nombre']) ?></h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Descripción</h5>
                <p class="card-text"><?= Helper::e($servicio['descripcion']) ?></p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>Duración</h5>
                        <p><i class="far fa-clock me-2 text-primary"></i> <?= $servicio['duracion'] ?> minutos</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Precio</h5>
                        <p><i class="fas fa-tag me-2 text-primary"></i> <?= Helper::formatPrice($servicio['precio']) ?></p>
                    </div>
                </div>
                
                <?php if (Auth::check()): ?>
                    <div class="d-grid gap-2 mt-4">
                        <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i> Reservar Ahora
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i> Debes <a href="<?= Helper::url('login') ?>">iniciar sesión</a> para reservar este servicio.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Beneficios</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check-circle me-2 text-success"></i> Reduce el estrés y la ansiedad
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle me-2 text-success"></i> Mejora la circulación sanguínea
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle me-2 text-success"></i> Alivia dolores musculares
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle me-2 text-success"></i> Promueve una sensación de bienestar general
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información Adicional</h5>
            </div>
            <div class="card-body">
                <h6>Recomendaciones</h6>
                <ul class="mb-4">
                    <li>Llegar 10 minutos antes de la cita</li>
                    <li>Usar ropa cómoda</li>
                    <li>Informar sobre cualquier condición médica</li>
                </ul>
                
                <h6>Contraindicaciones</h6>
                <ul>
                    <li>Fiebre o procesos infecciosos</li>
                    <li>Lesiones recientes</li>
                    <li>Consultar con su médico en caso de embarazo</li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Servicios Relacionados</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?= Helper::url('servicios/1') ?>" class="list-group-item list-group-item-action">
                        Masaje Relajante
                    </a>
                    <a href="<?= Helper::url('servicios/2') ?>" class="list-group-item list-group-item-action">
                        Masaje Terapéutico
                    </a>
                    <a href="<?= Helper::url('servicios/3') ?>" class="list-group-item list-group-item-action">
                        Facial Hidratante
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

