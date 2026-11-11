
<?php
// Clase para manejar la conexion a la base de datos
class Database
{
    private static ?PDO $conn = null;

    // Obtener una unica conexion PDO
    public static function conectar(): PDO
    {
        if (self::$conn === null) {
            $dsn = 'mysql:host=localhost;dbname=workshop4;charset=utf8mb4';
            $usuario = 'root';
            $password = '';

            self::$conn = new PDO($dsn, $usuario, $password);

            self::$conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return self::$conn;
    }
}
