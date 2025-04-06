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
                    <a href="<?= Helper::url('trabajador/reservas') ?>" class="list-group-item list-group-item-action active">
                        <i class="fas fa-calendar-alt me-2"></i> Mis Reservas
                    </a>
                    <a href="<?= Helper::url('trabajador/valoraciones') ?>" class="list-group-item list-group-item-action">
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
        <h1 class="mb-4">Mis Reservas</h1>
        
        <div class="card">
            <div class="card-body">
                <?php if (empty($reservas)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No tienes reservas asignadas.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
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
                                        <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                        <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                        <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                        <td><?= $reserva['duracion'] ?> min</td>
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
                                            <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/confirmar') ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

