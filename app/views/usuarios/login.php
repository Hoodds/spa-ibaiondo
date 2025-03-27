<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Iniciar Sesión</h2>
                    
                    <form action="<?= Helper::url('login') ?>" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p>¿No tienes una cuenta? <a href="<?= Helper::url('registro') ?>">Regístrate</a></p>
                        <p>¿Eres trabajador? <a href="<?= Helper::url('trabajador/login') ?>">Acceso para trabajadores</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

