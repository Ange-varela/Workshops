
<?php
require_once __DIR__ . '/../Core/Database.php';

// Modelo encargado de consultar las provincias
class Provincia
{
    // Verificar si una provincia existe
    public static function existe(int $id): bool
    {
        $conn = Database::conectar();

        // Buscar la provincia por su ID
        $sql = 'SELECT id FROM provincias WHERE id = :id';

        $consulta = $conn->prepare($sql);
        $consulta->execute(['id' => $id]);

        return (bool) $consulta->fetch(PDO::FETCH_ASSOC);
    }

    
    // Obtener todas las provincias ordenadas por nombre
    public static function listar(): array
    {
        $conn = Database::conectar();

        $sql = 'SELECT id, nombre FROM provincias ORDER BY nombre';

        $consulta = $conn->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

}
