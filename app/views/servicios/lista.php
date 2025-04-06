<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Nuestros Servicios</h1>
        <p class="lead mb-5">Descubre nuestra amplia gama de servicios diseñados para tu bienestar y relajación.</p>
    </div>
</div>

<div class="row">
    <?php foreach ($servicios as $servicio): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?= Helper::e($servicio['nombre']) ?></h5>
                    <p class="card-text"><?= Helper::e(substr($servicio['descripcion'], 0, 100)) ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            <i class="far fa-clock me-1"></i> <?= $servicio['duracion'] ?> minutos
                        </span>
                        <span class="fw-bold"><?= Helper::formatPrice($servicio['precio']) ?></span>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0">
                    <div class="d-grid gap-2">
                        <a href="<?= Helper::url('servicios/' . $servicio['id']) ?>" class="btn btn-outline-primary">
                            Ver Detalles
                        </a>
                        <?php if (Auth::check()): ?>
                            <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i> Reservar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

