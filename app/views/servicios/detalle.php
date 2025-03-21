<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <img src="<?= Helper::asset('images/servicios/' . $servicio['id'] . '.jpg') ?>" 
                     alt="<?= Helper::e($servicio['nombre']) ?>" 
                     class="card-img-top" 
                     onerror="this.src='<?= Helper::asset('images/servicio-default.jpg') ?>'">
                <div class="card-body">
                    <h1 class="card-title"><?= Helper::e($servicio['nombre']) ?></h1>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="badge bg-primary me-2">
                                <i class="far fa-clock"></i> <?= $servicio['duracion'] ?> minutos
                            </span>
                            <span class="badge bg-info">
                                <i class="fas fa-tag"></i> <?= Helper::formatPrice($servicio['precio']) ?>
                            </span>
                        </div>
                        <?php if (Auth::check()): ?>
                            <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Reservar Ahora
                            </a>
                        <?php else: ?>
                            <a href="<?= Helper::url('login') ?>" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Reservar
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="card-subtitle mb-3">Descripción</h5>
                    <p class="card-text"><?= nl2br(Helper::e($servicio['descripcion'])) ?></p>
                    
                    <h5 class="card-subtitle mb-3 mt-4">Beneficios</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Reduce el estrés y la ansiedad</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Mejora la circulación sanguínea</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Alivia dolores musculares</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Promueve la relajación profunda</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Mejora la calidad del sueño</li>
                    </ul>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= Helper::url('servicios') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Servicios
                        </a>
                        <?php if (Auth::check()): ?>
                            <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Reservar Ahora
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>