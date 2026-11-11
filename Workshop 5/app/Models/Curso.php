
<?php
// Cargar la conexion a la base de datos
require_once __DIR__ . '/../Core/Database.php';

class Curso
{
    // Obtener todos los cursos ordenados por nombre
    public static function listar(): array
    {
        $conn = Database::conectar();

        $sql = 'SELECT * FROM cursos ORDER BY nombre';

        $consulta = $conn->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    
    // Buscar un curso por su ID
    public static function buscarPorId(int $id): ?array
    {
        $conn = Database::conectar();

        $consulta = $conn->prepare(
            'SELECT * FROM cursos WHERE id = :id'
        );
        $consulta->execute(['id' => $id]);

        $curso = $consulta->fetch(PDO::FETCH_ASSOC);

        return $curso ?: null;
    }

    // Verificar si el codigo pertenece a otro curso
    public static function existeCodigo(string $codigo, ?int $excluirId = null): bool
    {
        $conn = Database::conectar();

        $sql = 'SELECT id FROM cursos WHERE codigo_curso = :codigo';
        $parametros = ['codigo' => $codigo];

        if ($excluirId !== null) {
            $sql .= ' AND id != :id';
            $parametros['id'] = $excluirId;
        }

        $consulta = $conn->prepare($sql);
        $consulta->execute($parametros);

        return (bool) $consulta->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo curso
    public static function crear(
        string $nombre,
        string $codigo,
        int $carreraId,
        int $creditos,
        string $descripcion
    ): bool {
        $conn = Database::conectar();

        $sql = 'INSERT INTO cursos
                (nombre, codigo_curso, carrera_id, creditos, descripcion)
                VALUES (:nombre, :codigo, :carrera, :creditos, :descripcion)';

        $consulta = $conn->prepare($sql);

        return $consulta->execute([
            'nombre' => $nombre,
            'codigo' => $codigo,
            'carrera' => $carreraId,
            'creditos' => $creditos,
            'descripcion' => $descripcion
        ]);
    }

    // Actualizar un curso existente
    public static function actualizar(
        int $id,
        string $nombre,
        string $codigo,
        int $carreraId,
        int $creditos,
        string $descripcion
    ): bool {
        $conn = Database::conectar();

        $sql = 'UPDATE cursos
                SET nombre = :nombre,
                    codigo_curso = :codigo,
                    carrera_id = :carrera,
                    creditos = :creditos,
                    descripcion = :descripcion
                WHERE id = :id';

        $consulta = $conn->prepare($sql);

        return $consulta->execute([
            'id' => $id,
            'nombre' => $nombre,
            'codigo' => $codigo,
            'carrera' => $carreraId,
            'creditos' => $creditos,
            'descripcion' => $descripcion
        ]);
    }

    // Eliminar un curso por su ID
    public static function eliminar(int $id): bool
    {
        $conn = Database::conectar();

        $consulta = $conn->prepare(
            'DELETE FROM cursos WHERE id = :id'
        );

        return $consulta->execute(['id' => $id]);
    }

}
