<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Trabajadores</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoTrabajadorModal">
            <i class="fas fa-plus"></i> Nuevo Trabajador
        </button>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trabajadores)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay trabajadores registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($trabajadores as $trabajador): ?>
                                <tr>
                                    <td><?= $trabajador['id'] ?></td>
                                    <td><?= Helper::e($trabajador['nombre']) ?></td>
                                    <td><?= Helper::e($trabajador['email']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = '';
                                        switch ($trabajador['rol']) {
                                            case 'admin':
                                                $badgeClass = 'bg-danger';
                                                break;
                                            case 'recepcionista':
                                                $badgeClass = 'bg-info';
                                                break;
                                            case 'masajista':
                                                $badgeClass = 'bg-success';
                                                break;
                                            case 'terapeuta':
                                                $badgeClass = 'bg-warning';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($trabajador['rol']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#verTrabajadorModal<?= $trabajador['id'] ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarTrabajadorModal<?= $trabajador['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este trabajador?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Modal Ver Trabajador -->
                                <div class="modal fade" id="verTrabajadorModal<?= $trabajador['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalles del Trabajador</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>ID:</strong> <?= $trabajador['id'] ?></p>
                                                <p><strong>Nombre:</strong> <?= Helper::e($trabajador['nombre']) ?></p>
                                                <p><strong>Email:</strong> <?= Helper::e($trabajador['email']) ?></p>
                                                <p><strong>Rol:</strong> <span class="badge <?= $badgeClass ?>"><?= ucfirst($trabajador['rol']) ?></span></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal Editar Trabajador -->
                                <div class="modal fade" id="editarTrabajadorModal<?= $trabajador['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Trabajador</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="nombre<?= $trabajador['id'] ?>" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" id="nombre<?= $trabajador['id'] ?>" value="<?= Helper::e($trabajador['nombre']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email<?= $trabajador['id'] ?>" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email<?= $trabajador['id'] ?>" value="<?= Helper::e($trabajador['email']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="rol<?= $trabajador['id'] ?>" class="form-label">Rol</label>
                                                        <select class="form-select" id="rol<?= $trabajador['id'] ?>">
                                                            <option value="admin" <?= $trabajador['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                                            <option value="recepcionista" <?= $trabajador['rol'] == 'recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
                                                            <option value="masajista" <?= $trabajador['rol'] == 'masajista' ? 'selected' : '' ?>>Masajista</option>
                                                            <option value="terapeuta" <?= $trabajador['rol'] == 'terapeuta' ? 'selected' : '' ?>>Terapeuta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password<?= $trabajador['id'] ?>" class="form-label">Nueva Contraseña</label>
                                                        <input type="password" class="form-control" id="password<?= $trabajador['id'] ?>">
                                                        <div class="form-text">Dejar en blanco para mantener la contraseña actual.</div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-primary">Guardar Cambios</button>
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

<!-- Modal Nuevo Trabajador -->
<div class="modal fade" id="nuevoTrabajadorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="nuevoNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nuevoNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="nuevoEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoRol" class="form-label">Rol</label>
                        <select class="form-select" id="nuevoRol" required>
                            <option value="">Seleccionar rol</option>
                            <option value="admin">Administrador</option>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="masajista">Masajista</option>
                            <option value="terapeuta">Terapeuta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="nuevoPassword" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Crear Trabajador</button>
            </div>
        </div>
    </div>
</div>

