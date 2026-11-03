
<?php
// Cargar el controlador del perfil
require_once __DIR__ . '/../app/Controllers/PerfilController.php';

// Permitir solamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php');
    exit;
}

// Procesar la actualizacion del perfil
$controller = new PerfilController();
$controller->actualizar();
