const conexion = require('../config/database');

class Matricula {

    // Obtener todas las matrículas con los datos del curso
    static async listar() {
        const [matriculas] = await conexion.query(
            `SELECT m.id, m.cedula, m.nombre_estudiante, m.correo, m.fecha_matricula,
                    c.nombre AS curso, c.codigo_curso
             FROM matriculas m
             INNER JOIN cursos c ON c.id = m.curso_id
             ORDER BY m.fecha_matricula DESC, m.id DESC`
        );

        return matriculas;
    }

    // Buscar una matrícula con los datos del curso y la carrera
    static async buscarPorId(id) {
        const [matriculas] = await conexion.query(
            `SELECT m.id, m.cedula, m.nombre_estudiante, m.correo, m.fecha_matricula,
                    c.nombre AS curso, c.codigo_curso, c.creditos,
                    ca.nombre AS carrera
             FROM matriculas m
             INNER JOIN cursos c ON c.id = m.curso_id
             INNER JOIN carreras ca ON ca.id = c.carrera_id
             WHERE m.id = ?`,
            [id]
        );

        return matriculas[0];
    }

    // Registrar una matrícula
    static async crear({ cedula, nombre_estudiante, correo, curso_id }) {
        const [resultado] = await conexion.query(
            `INSERT INTO matriculas (cedula, nombre_estudiante, correo, curso_id)
             VALUES (?, ?, ?, ?)`,
            [cedula, nombre_estudiante, correo, curso_id]
        );

        return resultado.insertId;
    }
}

module.exports = Matricula;
