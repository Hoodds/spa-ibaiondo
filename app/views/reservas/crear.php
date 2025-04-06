<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">Reservar Servicio</h1>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detalles del Servicio</h5>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= Helper::e($servicio['nombre']) ?></h5>
                <p class="card-text"><?= Helper::e($servicio['descripcion']) ?></p>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><i class="far fa-clock me-2 text-primary"></i> Duración: <?= $servicio['duracion'] ?> minutos</p>
                    </div>
                    <div class="col-md-6">
                        <p><i class="fas fa-tag me-2 text-primary"></i> Precio: <?= Helper::formatPrice($servicio['precio']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Formulario de Reserva</h5>
            </div>
            <div class="card-body">
                <form action="<?= Helper::url('reservas/crear') ?>" method="post" id="reservaForm">
                    <input type="hidden" name="csrf_token" value="<?= Helper::generateCSRFToken() ?>">
                    <input type="hidden" name="id_servicio" value="<?= $servicio['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_trabajador" class="form-label">Profesional</label>
                        <select class="form-select" id="id_trabajador" name="id_trabajador" required disabled>
                            <option value="">Seleccione una fecha primero</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="hora" class="form-label">Hora</label>
                        <select class="form-select" id="hora" name="hora" required disabled>
                            <option value="">Seleccione un profesional primero</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="btnReservar" disabled>
                            <i class="fas fa-calendar-check me-2"></i> Confirmar Reserva
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Información</h5>
            </div>
            <div class="card-body">
                <h6>Política de Cancelación</h6>
                <p>Puede cancelar su reserva hasta 24 horas antes de la cita sin cargo alguno.</p>
                
                <h6>Recomendaciones</h6>
                <ul>
                    <li>Llegue 10 minutos antes de su cita</li>
                    <li>Use ropa cómoda</li>
                    <li>Informe sobre cualquier condición médica relevante</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha');
    const trabajadorSelect = document.getElementById('id_trabajador');
    const horaSelect = document.getElementById('hora');
    const btnReservar = document.getElementById('btnReservar');
    
    fechaInput.addEventListener('change', function() {
        if (this.value) {
            // Obtener disponibilidad para la fecha seleccionada
            fetch('<?= Helper::url('reservas/getDisponibilidad') ?>?id_servicio=<?= $servicio['id'] ?>&fecha=' + this.value)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    
                    // Limpiar y habilitar el select de trabajadores
                    trabajadorSelect.innerHTML = '<option value="">Seleccione un profesional</option>';
                    trabajadorSelect.disabled = false;
                    
                    // Añadir opciones de trabajadores
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_trabajador;
                        option.textContent = item.nombre_trabajador;
                        option.dataset.horas = JSON.stringify(item.horas_disponibles);
                        trabajadorSelect.appendChild(option);
                    });
                    
                    // Si no hay disponibilidad
                    if (data.length === 0) {
                        trabajadorSelect.innerHTML = '<option value="">No hay disponibilidad para esta fecha</option>';
                        trabajadorSelect.disabled = true;
                        horaSelect.innerHTML = '<option value="">No hay horas disponibles</option>';
                        horaSelect.disabled = true;
                        btnReservar.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al obtener disponibilidad');
                });
        } else {
            trabajadorSelect.innerHTML = '<option value="">Seleccione una fecha primero</option>';
            trabajadorSelect.disabled = true;
            horaSelect.innerHTML = '<option value="">Seleccione un profesional primero</option>';
            horaSelect.disabled = true;
            btnReservar.disabled = true;
        }
    });
    
    trabajadorSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const horasDisponibles = JSON.parse(selectedOption.dataset.horas);
            
            // Limpiar y habilitar el select de horas
            horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
            horaSelect.disabled = false;
            
            // Añadir opciones de horas
            horasDisponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                horaSelect.appendChild(option);
            });
            
            // Si no hay horas disponibles
            if (horasDisponibles.length === 0) {
                horaSelect.innerHTML = '<option value="">No hay horas disponibles</option>';
                horaSelect.disabled = true;
                btnReservar.disabled = true;
            }
        } else {
            horaSelect.innerHTML = '<option value="">Seleccione un profesional primero</option>';
            horaSelect.disabled = true;
            btnReservar.disabled = true;
        }
    });
    
    horaSelect.addEventListener('change', function() {
        btnReservar.disabled = !this.value;
    });
});
</script>

