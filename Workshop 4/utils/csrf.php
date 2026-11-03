
<?php
// Iniciar la sesion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear un token de seguridad
function obtenerTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Validar el token enviado
function validarTokenCSRF(): void
{
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals(obtenerTokenCSRF(), $token)
    ) {
        http_response_code(403);
        exit('Solicitud no autorizada.');
    }
}
