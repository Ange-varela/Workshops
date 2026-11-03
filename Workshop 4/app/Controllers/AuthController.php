
<?php
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Provincia.php';

// Controlador encargado del inicio de sesion
class AuthController
{
    // Mostrar el formulario de inicio de sesion
    public function mostrarLogin(): void
    {
        // Obtener el usuario recordado
        $rememberedUsername = $_COOKIE['remembered_username'] ?? '';

        // Preparar los mensajes de error
        $errores = [
            'invalid_input' => 'Complete correctamente todos los campos.',
            'invalid_credentials' => 'El usuario o la contraseña son incorrectos.',
            'login_required' => 'Debe iniciar sesion para acceder.',
            'session_expired' => 'Su sesion expiro por inactividad. Inicie sesion nuevamente.'
        ];

        // Preparar los mensajes de exito
        $mensajes = [
            'logged_out' => 'Ha cerrado sesion correctamente.'
        ];

        // Obtener los mensajes de la URL
        $codigoError = $_GET['error'] ?? '';
        $codigoExito = $_GET['success'] ?? '';
        $registroExitoso = ($_GET['registro'] ?? '') === 'exitoso';

        // Mostrar la vista del login
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/auth/login.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }


    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener los datos del formulario
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        // Validar los datos recibidos
        if (
            !is_string($username)
            || !is_string($password)
            || trim($username) === ''
            || strlen($username) > 50
            || $password === ''
        ) {
            header('Location: /Workshops/Workshop%204/public/login.php?error=invalid_input');
            exit;
        }

        // Buscar el usuario mediante el modelo
        try {
            $usuario = Usuario::buscarPorUsername(trim($username));
        } catch (PDOException $exception) {
            error_log('Error de autenticacion: ' . $exception->getMessage());
            header('Location: /Workshops/Workshop%204/public/login.php?error=invalid_credentials');
            exit;
        }

        // Verificar las credenciales
        if ($usuario === null || !password_verify($password, $usuario['password_hash'])) {
            header('Location: /Workshops/Workshop%204/public/login.php?error=invalid_credentials');
            exit;
        }

        // Crear la sesion del usuario
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $usuario['id'];
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['last_activity'] = time();

        // Configurar la cookie
        $cookieOptions = [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/Workshops/Workshop%204/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax'
        ];

        // Recordar o eliminar el nombre de usuario
        if (isset($_POST['remember_me'])) {
            setcookie('remembered_username', $usuario['username'], $cookieOptions);
        } else {
            $cookieOptions['expires'] = time() - 3600;
            setcookie('remembered_username', '', $cookieOptions);
        }

        // Redirigir al panel principal
       header('Location: /Workshops/Workshop%204/public/dashboard.php');
        exit;

        
    }
    
    // Procesar el registro de usuarios
    public function registrar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Permitir solamente solicitudes POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Workshops/Workshop%204/public/registro.php');
            exit;
        }

        // Obtener los datos del formulario
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $provinciaId = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

        // Guardar los datos para recuperarlos si ocurre un error
        $_SESSION['registro_datos'] = [
            'username' => is_string($username) ? trim($username) : '',
            'provincia_id' => $provinciaId ?: ''
        ];

        // Validar los campos
        if (
            !is_string($username)
            || trim($username) === ''
            || strlen($username) > 50
            || !is_string($password)
            || $password === ''
            || !$provinciaId
        ) {
            header('Location: /Workshops/Workshop%204/public/registro.php?error=datos');
            exit;
        }

        // Validar la longitud de la contrasena
        if (strlen($password) < 8) {
            header('Location: /Workshops/Workshop%204/public/registro.php?error=longitud');
            exit;
        }

        // Verificar que las contrasenas coincidan
        if (!is_string($confirmPassword) || $password !== $confirmPassword) {
            header('Location: /Workshops/Workshop%204/public/registro.php?error=password');
            exit;
        }

        try {
            // Comprobar que la provincia exista
            if (!Provincia::existe($provinciaId)) {
                header('Location: /Workshops/Workshop%204/public/registro.php?error=provincia');
                exit;
            }

            // Comprobar que el usuario no exista
            if (Usuario::existe(trim($username))) {
                header('Location: /Workshops/Workshop%204/public/registro.php?error=existe');
                exit;
            }

            // Proteger la contrasena
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Guardar el usuario mediante el modelo
            Usuario::registrar(trim($username), $passwordHash, $provinciaId);

            // Limpiar los datos temporales
            unset($_SESSION['registro_datos']);

            // Mostrar el registro exitoso
            header('Location: /Workshops/Workshop%204/public/registro.php?registro=exitoso');
            exit;
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            header('Location: /Workshops/Workshop%204/public/registro.php?error=servidor');
            exit;
        }
    }

    
    // Mostrar el formulario de registro
    public function mostrarRegistro(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener las provincias desde el modelo
        try {
            $provincias = Provincia::listar();
        } catch (PDOException $exception) {
            error_log('Error al consultar provincias: ' . $exception->getMessage());
            $provincias = [];
        }

        // Recuperar los datos anteriores del formulario
        $datosRegistro = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_datos']);

        $usernameAnterior = $datosRegistro['username'] ?? '';
        $provinciaAnterior = $datosRegistro['provincia_id'] ?? '';

        // Preparar los mensajes de error
        $errores = [
            'password' => 'Las contraseñas no coinciden.',
            'longitud' => 'La contraseña debe tener al menos 8 caracteres.',
            'datos' => 'Complete correctamente todos los campos.',
            'provincia' => 'La provincia seleccionada no es valida.',
            'existe' => 'El nombre de usuario ya esta registrado.',
            'servidor' => 'Ocurrio un error al registrar el usuario.'
        ];

        $codigoError = $_GET['error'] ?? '';

        // Cargar la vista del registro
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/auth/registro.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }


}
