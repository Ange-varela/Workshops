
<?php
// Obtener la URL de la fotografia del usuario
function profilePictureUrl(string $nombreArchivo): string
{
    // Utilizar solamente el nombre del archivo
    $nombreArchivo = basename($nombreArchivo);

    // Construir la ruta publica de la fotografia
    return '/Workshops/Workshop%204/uploads/profile_pictures/'
        . rawurlencode($nombreArchivo);
}

// Guardar una fotografia de perfil
function storeProfilePicture(array $archivo): array
{
    // Verificar errores de subida
    if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['error' => 'upload_failed'];
    }

    // Validar el tamano maximo de 2 MB
    if ($archivo['size'] > 2 * 1024 * 1024) {
        return ['error' => 'picture_too_large'];
    }

    // Verificar que el archivo sea una imagen valida
    $rutaTemporal = $archivo['tmp_name'] ?? '';

    if (!is_string($rutaTemporal) || !is_uploaded_file($rutaTemporal)) {
        return ['error' => 'upload_failed'];
    }

    $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($rutaTemporal);

    $extensiones = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    if (!isset($extensiones[$tipo])) {
        return ['error' => 'invalid_picture'];
    }

    // Preparar la carpeta de fotografias
    $carpeta = __DIR__ . '/../uploads/profile_pictures/';

    if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
        return ['error' => 'upload_failed'];
    }

    // Generar un nombre unico para la fotografia
    $nombre = bin2hex(random_bytes(16)) . '.' . $extensiones[$tipo];
    $destino = $carpeta . $nombre;

    // Guardar el archivo
    if (!move_uploaded_file($rutaTemporal, $destino)) {
        return ['error' => 'upload_failed'];
    }

    return ['filename' => $nombre];
}


// Eliminar una fotografia de perfil

function deleteProfilePicture(string $nombreArchivo): void
{
    // Evitar rutas fuera de la carpeta de fotografias
    if ($nombreArchivo === '' || basename($nombreArchivo) !== $nombreArchivo) {
        return;
    }

    $ruta = __DIR__ . '/../uploads/profile_pictures/' . $nombreArchivo;

    if (is_file($ruta)) {
        unlink($ruta);
    }
}
