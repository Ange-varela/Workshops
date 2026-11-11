
<?php
// Cargar el controlador de autenticacion
require_once __DIR__ . '/app/Controllers/AuthController.php';

// Mostrar el formulario de registro
$controller = new AuthController();
$controller->mostrarRegistro();
