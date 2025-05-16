<div class="container py-4">
    <h1 class="mb-4">Mis Reservas Asignadas</h1>
    
    <?php if (empty($reservas)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No tienes reservas asignadas.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Fecha y Hora</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?= $reserva['id'] ?></td>
                                    <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                    <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                    <td><?= $reserva['duracion'] ?> min</td>
                                    <td>
                                        <?php if ($reserva['estado'] == 'pendiente'): ?>
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        <?php elseif ($reserva['estado'] == 'confirmada'): ?>
                                            <span class="badge bg-success">Confirmada</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($reserva['estado'] == 'pendiente'): ?>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/completar') ?>" class="btn btn-sm btn-success">Confirmar</a>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/cancelar') ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">Cancelar</a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detalleReserva<?= $reserva['id'] ?>">
                                            <i class="fas fa-info-circle"></i> Detalles
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal de detalles -->
                                <div class="modal fade fixed-modal" id="detalleReserva<?= $reserva['id'] ?>" tabindex="-1" aria-hidden="true" data-bs-focus="false">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles de la Reserva #<?= $reserva['id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Cliente:</strong> <?= Helper::e($reserva['nombre_usuario']) ?></p>
                                                <p><strong>Servicio:</strong> <?= Helper::e($reserva['nombre_servicio']) ?></p>
                                                <p><strong>Fecha y Hora:</strong> <?= Helper::formatDate($reserva['fecha_hora']) ?></p>
                                                <p><strong>Duración:</strong> <?= $reserva['duracion'] ?> minutos</p>
                                                <p><strong>Precio:</strong> <?= Helper::formatPrice($reserva['precio']) ?></p>
                                                <p><strong>Estado:</strong> 
                                                    <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                                    <?php elseif ($reserva['estado'] == 'confirmada'): ?>
                                                        <span class="badge bg-success">Confirmada</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Cancelada</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

