
<?php
// Cargar los modelos y la autenticacion
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Provincia.php';
require_once __DIR__ . '/../../utils/auth.php';
require_once __DIR__ . '/../../utils/profile_pictures.php';

class PerfilController
{
    // Mostrar el perfil del usuario
    public function index(): void
    {
        // Verificar que exista una sesion activa
        verificarSesion();

        // Obtener el ID del usuario
        $usuarioId = (int) $_SESSION['user_id'];

        try {
            // Consultar los datos mediante los modelos
            $usuario = Usuario::buscarPorId($usuarioId);
            $provincias = Provincia::listar();

            // Verificar que el usuario exista
            if ($usuario === null) {
                header('Location: /Workshops/Workshop%204/public/login.php');
                exit;
            }
        } catch (PDOException $exception) {
            error_log('Error al consultar perfil: ' . $exception->getMessage());
            http_response_code(500);
            echo 'Ocurrio un error al cargar el perfil.';
            return;
        }

        // Preparar los mensajes de error
        $errores = [
            'datos' => 'Complete correctamente los datos.',
            'provincia' => 'Seleccione una provincia valida.',
            'existe' => 'El nombre de usuario ya esta registrado.',
            'password_incomplete' => 'Complete los tres campos de contraseña.',
            'password_too_short' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password_mismatch' => 'Las nuevas contraseñas no coinciden.',
            'wrong_password' => 'La contraseña actual es incorrecta.',
            'invalid_picture' => 'Seleccione una imagen JPG, PNG, GIF o WEBP.',
            'picture_too_large' => 'La fotografia no puede superar los 2 MB.',
            'upload_failed' => 'No se pudo subir la fotografia.',
            'servidor' => 'Ocurrio un error al actualizar el perfil.'
        ];

        $codigoError = $_GET['error'] ?? '';

        // Cargar la vista del perfil
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/usuarios/perfil.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    
    // Procesar la actualizacion del perfil
    public function actualizar(): void
    {
        verificarSesion();

        $rutaPerfil = '/Workshops/Workshop%204/public/perfil.php';

        // Permitir solamente solicitudes POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $rutaPerfil);
            exit;
        }

        // Obtener los datos del formulario
        $username = $_POST['username'] ?? '';
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $provinciaId = filter_input(INPUT_POST, 'provincia_id', FILTER_VALIDATE_INT);

        // Validar los tipos de datos
        if (
            !is_string($username) ||
            !is_string($currentPassword) ||
            !is_string($newPassword) ||
            !is_string($confirmPassword)
        ) {
            header('Location: ' . $rutaPerfil . '?error=datos');
            exit;
        }

        $username = trim($username);

        if ($username === '' || strlen($username) > 50 || !$provinciaId) {
            header('Location: ' . $rutaPerfil . '?error=datos');
            exit;
        }

        $usuarioId = (int) $_SESSION['user_id'];
        $nuevaFoto = null;
        $fotoAnterior = null;

        try {
            // Validar la provincia
            if (!Provincia::existe($provinciaId)) {
                header('Location: ' . $rutaPerfil . '?error=provincia');
                exit;
            }

            // Verificar que el username no este ocupado
            if (Usuario::existeOtro($username, $usuarioId)) {
                header('Location: ' . $rutaPerfil . '?error=existe');
                exit;
            }

            // Consultar los datos privados del usuario
            $usuario = Usuario::obtenerDatosPrivados($usuarioId);

            if ($usuario === null) {
                header('Location: ' . $rutaPerfil . '?error=datos');
                exit;
            }

            $fotoAnterior = $usuario['profile_picture'];
            $passwordHash = null;

            // Validar el cambio de contrasena opcional
            if (
                $currentPassword !== '' ||
                $newPassword !== '' ||
                $confirmPassword !== ''
            ) {
                if (
                    $currentPassword === '' ||
                    $newPassword === '' ||
                    $confirmPassword === ''
                ) {
                    header('Location: ' . $rutaPerfil . '?error=password_incomplete');
                    exit;
                }

                if (strlen($newPassword) < 8) {
                    header('Location: ' . $rutaPerfil . '?error=password_too_short');
                    exit;
                }

                if ($newPassword !== $confirmPassword) {
                    header('Location: ' . $rutaPerfil . '?error=password_mismatch');
                    exit;
                }

                if (!password_verify($currentPassword, $usuario['password_hash'])) {
                    header('Location: ' . $rutaPerfil . '?error=wrong_password');
                    exit;
                }

                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            // Procesar la fotografia opcional
            if (
                isset($_FILES['profile_picture']) &&
                ($_FILES['profile_picture']['error'] ?? UPLOAD_ERR_NO_FILE)
                    !== UPLOAD_ERR_NO_FILE
            ) {
                $resultado = storeProfilePicture($_FILES['profile_picture']);

                if (isset($resultado['error'])) {
                    header('Location: ' . $rutaPerfil . '?error=' . $resultado['error']);
                    exit;
                }

                $nuevaFoto = $resultado['filename'];
            }

            // Actualizar los datos mediante el modelo
            Usuario::actualizarPerfil(
                $usuarioId,
                $username,
                $provinciaId,
                $passwordHash,
                $nuevaFoto
            );

            // Eliminar la fotografia anterior si fue reemplazada
            if ($nuevaFoto !== null && !empty($fotoAnterior)) {
                deleteProfilePicture($fotoAnterior);
            }

            // Actualizar la sesion
            $_SESSION['username'] = $username;

            if ($nuevaFoto !== null) {
                $_SESSION['profile_picture'] = $nuevaFoto;
            }

            header('Location: ' . $rutaPerfil . '?actualizado=1');
            exit;

        } catch (Throwable $exception) {
            // Eliminar la fotografia nueva si ocurre un error
            if ($nuevaFoto !== null) {
                deleteProfilePicture($nuevaFoto);
            }

            error_log('Error al actualizar perfil: ' . $exception->getMessage());

            header('Location: ' . $rutaPerfil . '?error=servidor');
            exit;
        }
    }

}
