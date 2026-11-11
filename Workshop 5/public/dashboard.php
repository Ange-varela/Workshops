<?php
// Cargar el controlador del panel principal
require_once __DIR__ . '/../app/Controllers/DashboardController.php';

// Mostrar el panel mediante el controlador
$controller = new DashboardController();
$controller->index();