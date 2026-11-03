
<?php
// Cargar el controlador de autenticacion
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Permitir solamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registro.php');
    exit;
}

// Procesar el registro
$controller = new AuthController();
$controller->registrar();
