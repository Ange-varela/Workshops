
<?php
// Cargar el controlador del perfil
require_once __DIR__ . '/../app/Controllers/PerfilController.php';

// Mostrar el perfil del usuario
$controller = new PerfilController();
$controller->index();
