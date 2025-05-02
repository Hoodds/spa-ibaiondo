<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Reservas</h1>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form class="row g-3" method="GET" action="<?= Helper::url('/admin/reservas') ?>">
                <div class="col-md-3">
                    <label for="filtroFecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="filtroFecha" name="filtroFecha">
                </div>
                <div class="col-md-3">
                    <label for="filtroServicio" class="form-label">Servicio</label>
                    <select class="form-select" id="filtroServicio" name="filtroServicio">
                        <option value="">Todos los servicios</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= $servicio['id'] ?>"><?= Helper::e($servicio['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTrabajador" class="form-label">Trabajador</label>
                    <select class="form-select" id="filtroTrabajador" name="filtroTrabajador">
                        <option value="">Todos los trabajadores</option>
                        <?php foreach ($trabajadores as $trabajador): ?>
                            <option value="<?= $trabajador['id'] ?>"><?= Helper::e($trabajador['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroEstado" class="form-label">Estado</label>
                    <select class="form-select" id="filtroEstado" name="filtroEstado">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Trabajador</th>
                            <th>Fecha y Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservas)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay reservas registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?= $reserva['id'] ?></td>
                                    <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_trabajador']) ?></td>
                                    <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
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
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#verReservaModal<?= $reserva['id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarReservaModal<?= $reserva['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= Helper::url('/admin/reservas/eliminar/' . $reserva['id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Modal Ver Reserva -->
                                <div class="modal fade" id="verReservaModal<?= $reserva['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles de la Reserva</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>ID:</strong> <?= $reserva['id'] ?></p>
                                                <p><strong>Cliente:</strong> <?= Helper::e($reserva['nombre_usuario']) ?></p>
                                                <p><strong>Servicio:</strong> <?= Helper::e($reserva['nombre_servicio']) ?></p>
                                                <p><strong>Trabajador:</strong> <?= Helper::e($reserva['nombre_trabajador']) ?></p>
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
                                
                                <!-- Modal Editar Reserva -->
                                <div class="modal fade" id="editarReservaModal<?= $reserva['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Reserva</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="<?= Helper::url('/admin/reservas/editar') ?>" method="POST">
                                                    <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="estado<?= $reserva['id'] ?>" class="form-label">Estado</label>
                                                        <select class="form-select" id="estado<?= $reserva['id'] ?>" name="estado" required>
                                                            <option value="pendiente" <?= $reserva['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="confirmada" <?= $reserva['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                            <option value="cancelada" <?= $reserva['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="fecha<?= $reserva['id'] ?>" class="form-label">Fecha</label>
                                                        <?php $fecha = new DateTime($reserva['fecha_hora']); ?>
                                                        <input type="date" class="form-control" id="fecha<?= $reserva['id'] ?>" name="fecha" value="<?= $fecha->format('Y-m-d') ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="hora<?= $reserva['id'] ?>" class="form-label">Hora</label>
                                                        <input type="time" class="form-control" id="hora<?= $reserva['id'] ?>" name="hora" value="<?= $fecha->format('H:i') ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="trabajador<?= $reserva['id'] ?>" class="form-label">Trabajador</label>
                                                        <select class="form-select" id="trabajador<?= $reserva['id'] ?>" name="id_trabajador" required>
                                                            <!-- Aquí se cargarían los trabajadores dinámicamente -->
                                                            <option value="<?= $reserva['id_trabajador'] ?>" selected><?= Helper::e($reserva['nombre_trabajador']) ?></option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Reserva -->
<div class="modal fade" id="nuevaReservaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="nuevoUsuario" class="form-label">Cliente</label>
                        <select class="form-select" id="nuevoUsuario" required>
                            <option value="">Seleccionar cliente</option>
                            <!-- Aquí se cargarían los usuarios dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoServicio" class="form-label">Servicio</label>
                        <select class="form-select" id="nuevoServicio" required>
                            <option value="">Seleccionar servicio</option>
                            <!-- Aquí se cargarían los servicios dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoTrabajador" class="form-label">Trabajador</label>
                        <select class="form-select" id="nuevoTrabajador" required>
                            <option value="">Seleccionar trabajador</option>
                            <!-- Aquí se cargarían los trabajadores dinámicamente -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaFecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="nuevaFecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaHora" class="form-label">Hora</label>
                        <input type="time" class="form-control" id="nuevaHora" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Estado</label>
                        <select class="form-select" id="nuevoEstado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Crear Reserva</button>
            </div>
        </div>
    </div>
</div>

