<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Panel de Control</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="<?= Helper::url('trabajador/dashboard') ?>" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="<?= Helper::url('trabajador/reservas') ?>" class="list-group-item list-group-item-action">
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
        <h1 class="mb-4">Dashboard de <?= Helper::e($_SESSION['trabajador_nombre']) ?></h1>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Reservas Hoy</h5>
                        <p class="card-text display-4"><?= count($reservasHoy) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Reservas Pendientes</h5>
                        <p class="card-text display-4"><?= count(array_filter($reservas, function($r) { return $r['estado'] === 'pendiente'; })) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Reservas</h5>
                        <p class="card-text display-4"><?= count($reservas) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Reservas para Hoy</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reservasHoy)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No tienes reservas programadas para hoy.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Hora</th>
                                    <th>Duración</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservasHoy as $reserva): ?>
                                    <tr>
                                        <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                        <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                        <td><?= date('H:i', strtotime($reserva['fecha_hora'])) ?></td>
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

