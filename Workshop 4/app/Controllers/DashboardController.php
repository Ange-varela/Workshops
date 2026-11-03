
<?php
// Cargar la autenticacion
require_once __DIR__ . '/../../utils/auth.php';

// Controlador del panel principal
class DashboardController
{
    public function index(): void
    {
        // Verificar que el usuario inicio sesion
        verificarSesion();

        // Obtener el nombre del usuario
        $username = $_SESSION['username'] ?? 'Usuario';

        // Mostrar la vista del panel
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/usuarios/dashboard.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }
}
