<?php
// Cargar el controlador de autenticacion
require_once __DIR__ . '/app/Controllers/AuthController.php';

// Crear el controlador
$controller = new AuthController();

// Mostrar la vista de inicio de sesion
$controller->mostrarLogin();