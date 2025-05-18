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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaReservaModal">
            <i class="fas fa-plus"></i> Nueva Reserva
        </button>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form class="row g-3" method="GET" action="<?= Helper::url('/admin/reservas') ?>">
                <div class="col-md-3">
                    <label for="filtroFecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="filtroFecha" name="filtroFecha" 
                           value="<?= isset($_GET['filtroFecha']) ? $_GET['filtroFecha'] : '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="filtroServicio" class="form-label">Servicio</label>
                    <select class="form-select" id="filtroServicio" name="filtroServicio">
                        <option value="">Todos los servicios</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= $servicio['id'] ?>" <?= isset($_GET['filtroServicio']) && $_GET['filtroServicio'] == $servicio['id'] ? 'selected' : '' ?>>
                                <?= Helper::e($servicio['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTrabajador" class="form-label">Trabajador</label>
                    <select class="form-select" id="filtroTrabajador" name="filtroTrabajador">
                        <option value="">Todos los trabajadores</option>
                        <?php foreach ($trabajadores as $trabajador): ?>
                            <option value="<?= $trabajador['id'] ?>" <?= isset($_GET['filtroTrabajador']) && $_GET['filtroTrabajador'] == $trabajador['id'] ? 'selected' : '' ?>>
                                <?= Helper::e($trabajador['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroEstado" class="form-label">Estado</label>
                    <select class="form-select" id="filtroEstado" name="filtroEstado">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?= isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="<?= Helper::url('/admin/reservas') ?>" class="btn btn-outline-secondary">Limpiar</a>
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
                    <tbody class="reservation-accordion">
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
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#verReserva<?= $reserva['id'] ?>" 
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning toggle-collapse" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#editarReserva<?= $reserva['id'] ?>" 
                                                aria-expanded="false">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= Helper::url('/admin/reservas/eliminar/' . $reserva['id']) ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Collapse Ver Reserva -->
                                <tr class="collapse-row">
                                    <td colspan="7" class="p-0">
                                        <div class="collapse" id="verReserva<?= $reserva['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $reserva['id'] ?></p>
                                                        <p><strong>Cliente:</strong> <?= Helper::e($reserva['nombre_usuario']) ?></p>
                                                        <p><strong>Email Cliente:</strong> <?= Helper::e($reserva['email_usuario'] ?? 'No disponible') ?></p>
                                                        <p><strong>Servicio:</strong> <?= Helper::e($reserva['nombre_servicio']) ?></p>
                                                        <p><strong>Trabajador:</strong> <?= Helper::e($reserva['nombre_trabajador']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
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
                                                        <p><strong>Fecha de creación:</strong> <?= isset($reserva['fecha_creacion']) ? Helper::formatDate($reserva['fecha_creacion']) : 'No disponible' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Collapse Editar Reserva -->
                                <tr class="collapse-row">
                                    <td colspan="7" class="p-0">
                                        <div class="collapse" id="editarReserva<?= $reserva['id'] ?>">
                                            <div class="card card-body m-2">
                                                <form action="<?= Helper::url('/admin/reservas/editar') ?>" method="POST" class="row g-3">
                                                    <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                                    <input type="hidden" name="id_servicio" value="<?= $reserva['id_servicio'] ?>">
                                                    <input type="hidden" name="id_usuario" value="<?= $reserva['id_usuario'] ?>">
                                                    
                                                    <div class="col-md-4">
                                                        <label for="estado<?= $reserva['id'] ?>" class="form-label">Estado</label>
                                                        <select class="form-select" id="estado<?= $reserva['id'] ?>" name="estado" required>
                                                            <option value="pendiente" <?= $reserva['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="confirmada" <?= $reserva['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                            <option value="cancelada" <?= $reserva['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label for="fecha<?= $reserva['id'] ?>" class="form-label">Fecha</label>
                                                        <?php $fecha = new DateTime($reserva['fecha_hora']); ?>
                                                        <input type="date" class="form-control fecha-reserva" 
                                                               id="fecha<?= $reserva['id'] ?>" 
                                                               name="fecha" 
                                                               value="<?= $fecha->format('Y-m-d') ?>" 
                                                               min="<?= date('Y-m-d') ?>"
                                                               data-reserva-id="<?= $reserva['id'] ?>"
                                                               data-servicio-id="<?= $reserva['id_servicio'] ?>"
                                                               required>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label for="hora<?= $reserva['id'] ?>" class="form-label">Hora</label>
                                                        <select class="form-select hora-reserva" 
                                                                id="hora<?= $reserva['id'] ?>" 
                                                                name="hora" 
                                                                data-hora-actual="<?= $fecha->format('H:i') ?>"
                                                                required>
                                                            <option value="<?= $fecha->format('H:i') ?>"><?= $fecha->format('H:i') ?></option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label for="trabajador<?= $reserva['id'] ?>" class="form-label">Trabajador</label>
                                                        <select class="form-select trabajador-reserva" 
                                                                id="trabajador<?= $reserva['id'] ?>" 
                                                                name="id_trabajador" 
                                                                data-trabajador-actual="<?= $reserva['id_trabajador'] ?>"
                                                                required>
                                                            <option value="<?= $reserva['id_trabajador'] ?>"><?= Helper::e($reserva['nombre_trabajador']) ?></option>
                                                        </select>
                                                        <div class="form-text">Selecciona una fecha para ver los trabajadores disponibles</div>
                                                    </div>
                                                    
                                                    <div class="col-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
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
                <form id="formNuevaReserva" action="<?= Helper::url('/admin/reservas/crear') ?>" method="POST">
                    <div class="mb-3">
                        <label for="nuevoUsuario" class="form-label">Cliente</label>
                        <select class="form-select" id="nuevoUsuario" name="id_usuario" required>
                            <option value="">Seleccionar cliente</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>"><?= Helper::e($usuario['nombre']) ?> (<?= Helper::e($usuario['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoServicio" class="form-label">Servicio</label>
                        <select class="form-select" id="nuevoServicio" name="id_servicio" required>
                            <option value="">Seleccionar servicio</option>
                            <?php foreach ($servicios as $servicio): ?>
                                <option value="<?= $servicio['id'] ?>"><?= Helper::e($servicio['nombre']) ?> - <?= Helper::formatPrice($servicio['precio']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoTrabajador" class="form-label">Trabajador</label>
                        <select class="form-select" id="nuevoTrabajador" name="id_trabajador" required>
                            <option value="">Seleccionar trabajador</option>
                            <?php foreach ($trabajadores as $trabajador): ?>
                                <option value="<?= $trabajador['id'] ?>"><?= Helper::e($trabajador['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaFecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="nuevaFecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaHora" class="form-label">Hora</label>
                        <input type="time" class="form-control" id="nuevaHora" name="hora" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Estado</label>
                        <select class="form-select" id="nuevoEstado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevaReserva" class="btn btn-primary">Crear Reserva</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestión de despliegues para que solo se muestre uno a la vez
    const toggleButtons = document.querySelectorAll('.toggle-collapse');
    const collapseElements = document.querySelectorAll('.collapse');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            
            // Cerrar todos los elementos desplegados excepto el actual
            collapseElements.forEach(collapse => {
                // Si no es el elemento seleccionado y está abierto, cerrarlo
                if ('#' + collapse.id !== target && collapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            });
        });
    });

    // Establecer fecha mínima como hoy para nuevas reservas
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('nuevaFecha').min = hoy;
    
    // Gestión de fechas en edición de reservas
    const fechasReserva = document.querySelectorAll('.fecha-reserva');
    
    fechasReserva.forEach(fechaInput => {
        fechaInput.addEventListener('change', function() {
            const reservaId = this.dataset.reservaId;
            const servicioId = this.dataset.servicioId;
            const fechaSeleccionada = this.value;
            
            if (!fechaSeleccionada) return;
            
            // Obtener trabajadores y horas disponibles para esta fecha y servicio
            fetch(`<?= Helper::url('reservas/disponibilidad') ?>?id_servicio=${servicioId}&fecha=${fechaSeleccionada}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    const trabajadorSelect = document.getElementById(`trabajador${reservaId}`);
                    const horaSelect = document.getElementById(`hora${reservaId}`);
                    const trabajadorActual = trabajadorSelect.dataset.trabajadorActual;
                    const horaActual = horaSelect.dataset.horaActual;
                    
                    // Limpiar selects
                    trabajadorSelect.innerHTML = '';
                    horaSelect.innerHTML = '';
                    
                    if (data.length === 0) {
                        // No hay disponibilidad
                        trabajadorSelect.innerHTML = '<option value="">No hay trabajadores disponibles</option>';
                        horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                        return;
                    }
                    
                    // Si la fecha seleccionada es la misma que la actual, intentamos mantener el trabajador y la hora
                    const fechaActual = document.getElementById(`fecha${reservaId}`).defaultValue;
                    const mantenerSeleccion = (fechaActual === fechaSeleccionada);
                    
                    // Llenar el select de trabajadores
                    let trabajadorEncontrado = false;
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_trabajador;
                        option.textContent = item.nombre_trabajador;
                        option.dataset.horas = JSON.stringify(item.horas_disponibles);
                        
                        // Si es el trabajador actual y mantenemos selección, seleccionarlo
                        if (mantenerSeleccion && item.id_trabajador == trabajadorActual) {
                            option.selected = true;
                            trabajadorEncontrado = true;
                            
                            // Cargar horarios de este trabajador
                            cargarHorarios(horaSelect, item.horas_disponibles, horaActual, mantenerSeleccion);
                        }
                        
                        trabajadorSelect.appendChild(option);
                    });
                    
                    // Si no se encontró el trabajador actual, seleccionar el primero
                    if (!trabajadorEncontrado && data.length > 0) {
                        trabajadorSelect.selectedIndex = 0;
                        cargarHorarios(horaSelect, JSON.parse(trabajadorSelect.options[0].dataset.horas), null, false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al obtener disponibilidad. Por favor, inténtalo de nuevo.');
                });
        });
    });
    
    // Cambiar horarios cuando se cambia de trabajador
    const trabajadoresReserva = document.querySelectorAll('.trabajador-reserva');
    
    trabajadoresReserva.forEach(trabajadorSelect => {
        trabajadorSelect.addEventListener('change', function() {
            if (!this.value) return;
            
            const reservaId = this.id.replace('trabajador', '');
            const horaSelect = document.getElementById(`hora${reservaId}`);
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.dataset.horas) {
                const horasDisponibles = JSON.parse(selectedOption.dataset.horas);
                cargarHorarios(horaSelect, horasDisponibles, null, false);
            }
        });
    });
    
    // Función para cargar horarios en un select
    function cargarHorarios(horaSelect, horasDisponibles, horaActual, mantenerHora) {
        horaSelect.innerHTML = '';
        
        if (horasDisponibles.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No hay horarios disponibles';
            horaSelect.appendChild(option);
            return;
        }
        
        let horaEncontrada = false;
        
        horasDisponibles.forEach(hora => {
            const option = document.createElement('option');
            option.value = hora;
            option.textContent = hora;
            
            // Si es la hora actual y mantenemos selección, seleccionarla
            if (mantenerHora && hora === horaActual) {
                option.selected = true;
                horaEncontrada = true;
            }
            
            horaSelect.appendChild(option);
        });
        
        // Si no se encontró la hora actual, seleccionar la primera
        if (!horaEncontrada && horasDisponibles.length > 0) {
            horaSelect.selectedIndex = 0;
        }
    }
    
    // Validación del formulario de nueva reserva
    const formNuevaReserva = document.getElementById('formNuevaReserva');
    if (formNuevaReserva) {
        formNuevaReserva.addEventListener('submit', function(e) {
            const servicio = document.getElementById('nuevoServicio').value;
            const usuario = document.getElementById('nuevoUsuario').value;
            const trabajador = document.getElementById('nuevoTrabajador').value;
            const fecha = document.getElementById('nuevaFecha').value;
            const hora = document.getElementById('nuevaHora').value;
            
            if (!servicio || !usuario || !trabajador || !fecha || !hora) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    }
    
    // Cargar disponibilidad inicial para cada desplegable al abrirse
    toggleButtons.forEach(button => {
        if (button.dataset.bsTarget && button.dataset.bsTarget.includes('editarReserva')) {
            button.addEventListener('click', function() {
                const reservaId = this.dataset.bsTarget.replace('#editarReserva', '');
                const fechaInput = document.getElementById(`fecha${reservaId}`);
                
                // Simular un cambio en la fecha para cargar los datos
                if (fechaInput) {
                    // Pequeño timeout para asegurar que el collapse se ha abierto
                    setTimeout(() => {
                        const event = new Event('change');
                        fechaInput.dispatchEvent(event);
                    }, 200);
                }
            });
        }
    });
});
</script>

