<div class="row">
 <div class="col-md-4">
     <div class="card mb-4">
         <div class="card-header bg-primary text-white">
             <h5 class="mb-0">Información Personal</h5>
         </div>
         <div class="card-body">
             <div class="text-center mb-4">
                 <div class="avatar-placeholder bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                     <i class="fas fa-user fa-3x text-primary"></i>
                 </div>
             </div>
             
             <h5 class="card-title"><?= Helper::e($usuario['nombre']) ?></h5>
             <p class="card-text">
                 <i class="fas fa-envelope me-2 text-primary"></i> <?= Helper::e($usuario['email']) ?>
             </p>
             <p class="card-text">
                 <i class="fas fa-calendar-alt me-2 text-primary"></i> Miembro desde: <?= Helper::formatDate($usuario['fecha_registro'], 'd/m/Y') ?>
             </p>
             
             <div class="d-grid gap-2 mt-3">
                 <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                     <i class="fas fa-edit me-2"></i> Editar Perfil
                 </button>
                 <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                     <i class="fas fa-key me-2"></i> Cambiar Contraseña
                 </button>
             </div>
         </div>
     </div>
 </div>
 
 <div class="col-md-8">
     <div class="card mb-4">
         <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Mis Reservas</h5>
             <a href="<?= Helper::url('reservas') ?>" class="btn btn-sm btn-light">
                 <i class="fas fa-calendar-plus me-1"></i> Ver Todas
             </a>
         </div>
         <div class="card-body">
             <?php if (empty($reservas)): ?>
                 <div class="alert alert-info">
                     <i class="fas fa-info-circle me-2"></i> No tienes reservas activas.
                 </div>
                 <div class="text-center mt-3">
                     <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">
                         <i class="fas fa-calendar-plus me-2"></i> Reservar Ahora
                     </a>
                 </div>
             <?php else: ?>
                 <div class="table-responsive">
                     <table class="table table-hover">
                         <thead>
                             <tr>
                                 <th>Servicio</th>
                                 <th>Fecha</th>
                                 <th>Estado</th>
                                 <th>Acciones</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php foreach (array_slice($reservas, 0, 5) as $reserva): ?>
                                 <tr>
                                     <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                     <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
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
                                                 <i class="fas fa-times"></i>
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
     
     <div class="card">
         <div class="card-header bg-primary text-white">
             <h5 class="mb-0">Servicios Recomendados</h5>
         </div>
         <div class="card-body">
             <div class="row">
                 <div class="col-md-6 mb-3">
                     <div class="card h-100">
                         <div class="card-body">
                             <h5 class="card-title">Masaje Relajante</h5>
                             <p class="card-text">Disfruta de un masaje con aceites esenciales para aliviar el estrés.</p>
                             <a href="<?= Helper::url('servicios/1') ?>" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-6 mb-3">
                     <div class="card h-100">
                         <div class="card-body">
                             <h5 class="card-title">Facial Hidratante</h5>
                             <p class="card-text">Tratamiento facial con productos naturales para hidratar tu piel.</p>
                             <a href="<?= Helper::url('servicios/3') ?>" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
</div>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
             <form action="<?= Helper::url('perfil/actualizar') ?>" method="post">
                 <input type="hidden" name="csrf_token" value="<?= Helper::generateCSRFToken() ?>">
                 
                 <div class="mb-3">
                     <label for="nombre" class="form-label">Nombre Completo</label>
                     <input type="text" class="form-control" id="nombre" name="nombre" value="<?= Helper::e($usuario['nombre']) ?>" required>
                 </div>
                 
                 <div class="mb-3">
                     <label for="email" class="form-label">Email</label>
                     <input type="email" class="form-control" id="email" name="email" value="<?= Helper::e($usuario['email']) ?>" required>
                 </div>
                 
                 <div class="d-grid">
                     <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
 <div class="modal-dialog">
     <div class="modal-content">
         <div class="modal-header">
             <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
             <form action="<?= Helper::url('perfil/cambiar-password') ?>" method="post">
                 <input type="hidden" name="csrf_token" value="<?= Helper::generateCSRFToken() ?>">
                 
                 <div class="mb-3">
                     <label for="current_password" class="form-label">Contraseña Actual</label>
                     <input type="password" class="form-control" id="current_password" name="current_password" required>
                 </div>
                 
                 <div class="mb-3">
                     <label for="new_password" class="form-label">Nueva Contraseña</label>
                     <input type="password" class="form-control" id="new_password" name="new_password" required>
                 </div>
                 
                 <div class="mb-3">
                     <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                     <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                 </div>
                 
                 <div class="d-grid">
                     <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
</div>

