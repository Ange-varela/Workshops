
<?php
// Cargar el modelo, la autenticacion y la seguridad
require_once __DIR__ . '/../../utils/csrf.php';
require_once __DIR__ . '/../Models/Curso.php';
require_once __DIR__ . '/../../utils/auth.php';

class CursoController
{
    // Mostrar la lista de cursos
    public function index(): void
    {
        verificarSesion();

        try {
            $cursos = Curso::listar();
        } catch (PDOException $exception) {
            error_log('Error al consultar cursos: ' . $exception->getMessage());

            http_response_code(500);
            echo 'Ocurrio un error al cargar los cursos.';
            return;
        }

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/cursos/index.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    // Mostrar el formulario para crear cursos
    public function mostrarCrear(): void
    {
        verificarSesion();

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/cursos/crear.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    // Guardar un curso nuevo
    public function crear(): void
    {
        verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos.php');
            exit;
        }

        // Validar el token de seguridad
        validarTokenCSRF();

        $nombre = trim($_POST['nombre'] ?? '');
        $codigo = trim($_POST['codigo_curso'] ?? '');
        $carreraId = filter_input(INPUT_POST, 'carrera_id', FILTER_VALIDATE_INT);
        $creditos = filter_input(INPUT_POST, 'creditos', FILTER_VALIDATE_INT);
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($nombre === '' || strlen($nombre) > 100 ||
            $codigo === '' || strlen($codigo) > 20 ||
            !$carreraId || $creditos === false ||
            $creditos === null || $creditos < 0) {
            header('Location: crear_curso.php?error=datos');
            exit;
        }

        try {
            if (Curso::existeCodigo($codigo)) {
                header('Location: crear_curso.php?error=existe');
                exit;
            }

            Curso::crear(
                $nombre,
                $codigo,
                $carreraId,
                $creditos,
                $descripcion
            );

            header('Location: cursos.php?creado=1');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header('Location: crear_curso.php?error=servidor');
            exit;
        }
    }

    // Mostrar el formulario de edicion
    public function mostrarEditar(): void
    {
        verificarSesion();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: cursos.php');
            exit;
        }

        $curso = Curso::buscarPorId($id);

        if (!$curso) {
            header('Location: cursos.php');
            exit;
        }

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/cursos/editar.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    // Actualizar un curso
    public function editar(): void
    {
        verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos.php');
            exit;
        }

        // Validar el token de seguridad
        validarTokenCSRF();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = trim($_POST['nombre'] ?? '');
        $codigo = trim($_POST['codigo_curso'] ?? '');
        $carreraId = filter_input(INPUT_POST, 'carrera_id', FILTER_VALIDATE_INT);
        $creditos = filter_input(INPUT_POST, 'creditos', FILTER_VALIDATE_INT);
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!$id || $nombre === '' || strlen($nombre) > 100 ||
            $codigo === '' || strlen($codigo) > 20 ||
            !$carreraId || $creditos === false ||
            $creditos === null || $creditos < 0) {
            header('Location: cursos.php?error=datos');
            exit;
        }

        try {
            if (!Curso::buscarPorId($id)) {
                header('Location: cursos.php?error=noexiste');
                exit;
            }

            if (Curso::existeCodigo($codigo, $id)) {
                header('Location: editar_curso.php?id=' . $id . '&error=existe');
                exit;
            }

            Curso::actualizar(
                $id,
                $nombre,
                $codigo,
                $carreraId,
                $creditos,
                $descripcion
            );

            header('Location: cursos.php?actualizado=1');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header('Location: cursos.php?error=servidor');
            exit;
        }
    }

    // Eliminar un curso
    public function eliminar(): void
    {
        verificarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: cursos.php');
            exit;
        }

        // Validar el token de seguridad
        validarTokenCSRF();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            header('Location: cursos.php?error=datos');
            exit;
        }

        try {
            Curso::eliminar($id);

            header('Location: cursos.php?eliminado=1');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header('Location: cursos.php?error=servidor');
            exit;
        }
    }
}
