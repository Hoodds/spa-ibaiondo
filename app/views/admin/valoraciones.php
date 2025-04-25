<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Valoraciones</h1>
        <a href="<?= Helper::url('admin/valoraciones/pendientes') ?>" class="btn btn-warning">
            Valoraciones Pendientes 
            <?php 
            $pendientes = isset($stats['valoraciones']['por_estado']['pendiente']) ? $stats['valoraciones']['por_estado']['pendiente'] : 0;
            if ($pendientes > 0): 
            ?>
                <span class="badge bg-danger"><?= $pendientes ?></span>
            <?php endif; ?>
        </a>
    </div>
    
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
                            <th>Estado</th>
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
                                <td><?= substr(Helper::e($valoracion['comentario']), 0, 50) ?>...</td>
                                <td><?= Helper::formatDate($valoracion['fecha_creacion']) ?></td>
                                <td>
                                    <?php if ($valoracion['estado'] == 'pendiente'): ?>
                                        <span class="badge bg-warning">Pendiente</span>
                                    <?php elseif ($valoracion['estado'] == 'aprobada'): ?>
                                        <span class="badge bg-success">Aprobada</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rechazada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($valoracion['estado'] == 'pendiente'): ?>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/aprobar') ?>" class="btn btn-sm btn-success" title="Aprobar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/rechazar') ?>" class="btn btn-sm btn-danger" title="Rechazar">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/eliminar') ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

