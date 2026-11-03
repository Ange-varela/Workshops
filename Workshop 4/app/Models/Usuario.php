
<?php
require_once __DIR__ . '/../Core/Database.php';

// Modelo encargado de consultar los usuarios
class Usuario
{
    // Buscar un usuario por su nombre
    public static function buscarPorUsername(string $username): ?array
    {
        $conn = Database::conectar();

        // Preparar la consulta SQL
        $sql = 'SELECT id, username, password_hash
                FROM usuarios
                WHERE username = :username
                LIMIT 1';

        $statement = $conn->prepare($sql);
        $statement->execute(['username' => $username]);

        // Obtener el usuario encontrado
        $usuario = $statement->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
        

    }

    // Verificar si un nombre de usuario ya existe
    public static function existe(string $username): bool
    {
        $conn = Database::conectar();

        $sql = 'SELECT id FROM usuarios WHERE username = :username';

        $consulta = $conn->prepare($sql);
        $consulta->execute(['username' => $username]);

        return (bool) $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Registrar un nuevo usuario
    public static function registrar(
        string $username,
        string $passwordHash,
        int $provinciaId
    ): bool {
        $conn = Database::conectar();

        $sql = 'INSERT INTO usuarios (username, password_hash, provincia_id)
                VALUES (:username, :password_hash, :provincia_id)';

        $consulta = $conn->prepare($sql);

        return $consulta->execute([
            'username' => $username,
            'password_hash' => $passwordHash,
            'provincia_id' => $provinciaId
        ]);
    }

    
    // Obtener los datos del perfil por ID
    public static function buscarPorId(int $id): ?array
    {
        $conn = Database::conectar();

        // Consultar el usuario y su provincia
        $sql = 'SELECT u.id, u.username, u.provincia_id,
                       u.profile_picture, p.nombre AS provincia
                FROM usuarios u
                LEFT JOIN provincias p ON u.provincia_id = p.id
                WHERE u.id = :id';

        $consulta = $conn->prepare($sql);
        $consulta->execute(['id' => $id]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    
    // Verificar si el username pertenece a otro usuario
    public static function existeOtro(string $username, int $id): bool
    {
        $conn = Database::conectar();

        $sql = 'SELECT id FROM usuarios
                WHERE username = :username AND id != :id';

        $consulta = $conn->prepare($sql);
        $consulta->execute([
            'username' => $username,
            'id' => $id
        ]);

        return (bool) $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener los datos necesarios para actualizar el perfil
    public static function obtenerDatosPrivados(int $id): ?array
    {
        $conn = Database::conectar();

        $sql = 'SELECT password_hash, profile_picture
                FROM usuarios
                WHERE id = :id';

        $consulta = $conn->prepare($sql);
        $consulta->execute(['id' => $id]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    // Actualizar los datos del perfil
    public static function actualizarPerfil(
        int $id,
        string $username,
        int $provinciaId,
        ?string $passwordHash,
        ?string $nuevaFoto
    ): bool {
        $conn = Database::conectar();

        // Preparar los campos obligatorios
        $campos = [
            'username = :username',
            'provincia_id = :provincia_id'
        ];

        $parametros = [
            'username' => $username,
            'provincia_id' => $provinciaId,
            'id' => $id
        ];

        // Agregar la contrasena solamente si se cambia
        if ($passwordHash !== null) {
            $campos[] = 'password_hash = :password_hash';
            $parametros['password_hash'] = $passwordHash;
        }

        // Agregar la fotografia solamente si se cambia
        if ($nuevaFoto !== null) {
            $campos[] = 'profile_picture = :profile_picture';
            $parametros['profile_picture'] = $nuevaFoto;
        }

        // Ejecutar la actualizacion
        $sql = 'UPDATE usuarios SET ' . implode(', ', $campos)
             . ' WHERE id = :id';

        $consulta = $conn->prepare($sql);

        return $consulta->execute($parametros);
    }

}
