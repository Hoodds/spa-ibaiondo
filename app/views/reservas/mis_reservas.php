<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Mis Reservas</h1>
    </div>
</div>

<?php if (empty($reservas)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> No tienes reservas activas.
    </div>
    <div class="text-center mt-4">
        <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-2"></i> Reservar Ahora
        </a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Profesional</th>
                            <th>Fecha y Hora</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                <td><?= Helper::e($reserva['nombre_trabajador']) ?></td>
                                <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                <td><?= $reserva['duracion'] ?> min</td>
                                <td><?= Helper::formatPrice($reserva['precio']) ?></td>
                                <td>
                                    <?php if ($reserva['estado'] == 'confirmada'): ?>
                                        <span class="badge bg-success">Confirmada</span>
                                    <?php elseif ($reserva['estado'] == 'pendiente'): ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($reserva['estado'] != 'cancelada'): ?>
                                        <a href="<?= Helper::url('reservas/' . $reserva['id'] . '/cancelar') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">
            <i class="fas fa-calendar-plus me-2"></i> Nueva Reserva
        </a>
    </div>
<?php endif; ?>