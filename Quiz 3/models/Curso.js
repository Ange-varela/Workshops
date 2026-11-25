const conexion = require('../config/database');

class Curso {

    // Obtener todos los cursos con el nombre de su carrera
    static async listar() {
        const [cursos] = await conexion.query(
            `SELECT c.id, c.nombre, c.codigo_curso, c.creditos, c.carrera_id,
                    ca.nombre AS carrera
             FROM cursos c
             INNER JOIN carreras ca ON ca.id = c.carrera_id
             ORDER BY c.nombre`
        );

        return cursos;
    }

    // Buscar un curso por su ID
    static async buscarPorId(id) {
        const [cursos] = await conexion.query(
            `SELECT c.id, c.nombre, c.codigo_curso, c.creditos, c.carrera_id,
                    ca.nombre AS carrera
             FROM cursos c
             INNER JOIN carreras ca ON ca.id = c.carrera_id
             WHERE c.id = ?`,
            [id]
        );

        return cursos[0];
    }

    // Registrar un curso
    static async crear({ nombre, codigo_curso, creditos, carrera_id }) {
        const [resultado] = await conexion.query(
            `INSERT INTO cursos (nombre, codigo_curso, creditos, carrera_id)
             VALUES (?, ?, ?, ?)`,
            [nombre, codigo_curso, creditos, carrera_id]
        );

        return resultado.insertId;
    }

    // Actualizar un curso
    static async actualizar(id, { nombre, codigo_curso, creditos, carrera_id }) {
        const [resultado] = await conexion.query(
            `UPDATE cursos
             SET nombre = ?, codigo_curso = ?, creditos = ?, carrera_id = ?
             WHERE id = ?`,
            [nombre, codigo_curso, creditos, carrera_id, id]
        );

        return resultado.affectedRows;
    }

    // Eliminar un curso
    static async eliminar(id) {
        const [resultado] = await conexion.query(
            'DELETE FROM cursos WHERE id = ?',
            [id]
        );

        return resultado.affectedRows;
    }
}

module.exports = Curso;
