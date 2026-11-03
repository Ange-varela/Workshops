
<?php
// Cargar el controlador de cursos
require_once __DIR__ . '/../app/Controllers/CursoController.php';

// Mostrar el listado de cursos
$controller = new CursoController();
$controller->index();
