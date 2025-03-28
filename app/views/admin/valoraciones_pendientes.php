<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Valoraciones Pendientes de Moderación</h1>
        <a href="<?= Helper::url('admin/valoraciones') ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Volver a Todas las Valoraciones
        </a>
    </div>
    
    <?php if (empty($valoraciones)): ?>
        <div class="alert alert-success">
            <p class="mb-0">No hay valoraciones pendientes de moderación.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Servicio</th>
                                <th>Puntuación</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($valoraciones as $valoracion): ?>
                                <tr>
                                    <td><?= $valoracion['id'] ?></td>
                                    <td><?= Helper::e($valoracion['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($valoracion['nombre_servicio']) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $valoracion['puntuacion']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </td>
                                    <td><?= Helper::e($valoracion['comentario']) ?></td>
                                    <td><?= Helper::formatDate($valoracion['fecha_creacion']) ?></td>
                                    <td>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/aprobar') ?>" class="btn btn-success" title="Aprobar">
                                            <i class="fas fa-check"></i> Aprobar
                                        </a>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/rechazar') ?>" class="btn btn-danger" title="Rechazar">
                                            <i class="fas fa-times"></i> Rechazar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

