
<?php
// Cargar el controlador de autenticacion
require_once __DIR__ . '/../app/Controllers/AuthController.php';

$controller = new AuthController();

// Mostrar el formulario o procesar el inicio de sesion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->mostrarLogin();
}
