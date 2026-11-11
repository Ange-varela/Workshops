<div class="container mt-5">
    <h1 class="text-center">Bienvenido a mi sitio web</h1>
    <p class="text-center">Inicia sesion para acceder a las funciones del sistema.</p>
</div>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
            <h4>Iniciar sesion</h4>
        </div>

        <div class="card-body">
          <!-- Mostrar los mensajes de error -->
          <?php if (is_string($codigoError) && isset($errores[$codigoError])): ?>
              <div class="alert alert-danger text-center">
                  <?= htmlspecialchars($errores[$codigoError], ENT_QUOTES, 'UTF-8') ?>
              </div>
          <?php endif; ?>
          <!-- Mostrar los mensajes de exito -->
          <?php if (is_string($codigoExito) && isset($mensajes[$codigoExito])): ?>
              <div class="alert alert-success text-center">
                  <?= htmlspecialchars($mensajes[$codigoExito], ENT_QUOTES, 'UTF-8') ?>
              </div>
          <?php endif; ?>
          <!-- Mostrar el mensaje de registro exitoso -->
          <?php if (($_GET['registro'] ?? '') === 'exitoso'): ?>
              <div class="alert alert-success text-center">
                  Usuario registrado correctamente.
              </div>
          <?php endif; ?>
          <!-- Formulario para iniciar sesion -->
          <form action="/Workshops/Workshop%204/public/login.php" method="POST">
              <!-- Ingresar el nombre de usuario -->
              <div class="form-group">
                  <label for="username">Nombre de usuario</label>
                  <input type="text" class="form-control" id="username" name="username"
                          value="<?= htmlspecialchars($rememberedUsername, ENT_QUOTES, 'UTF-8') ?>" required>
              </div>
              <!-- Ingresar la contrasena -->
              <div class="form-group">
                  <label for="password">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <!-- Recordar el nombre de usuario -->
              <div class="form-group form-check">
                  <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me"
                          <?= $rememberedUsername !== '' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="remember_me">Recordar usuario</label>
              </div>
              <!-- Boton para iniciar sesion -->
              <button type="submit" class="btn btn-primary btn-block">Iniciar sesion</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>