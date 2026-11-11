
const conexion = require('../config/database');

class Curso {

    // Obtener todos los cursos
    static async listar() {
        const [cursos] = await conexion.query(
            'SELECT * FROM cursos ORDER BY id DESC'
        );

        return cursos;
    }

    // Buscar un curso por su ID
    static async buscarPorId(id) {
        const [cursos] = await conexion.query(
            'SELECT * FROM cursos WHERE id = ?',
            [id]
        );

        return cursos[0];
    }

    // Registrar un curso
    static async crear(datos) {
        const { nombre, codigo_curso, carrera_id, creditos, descripcion } = datos;

        const [resultado] = await conexion.query(
            `INSERT INTO cursos
            (nombre, codigo_curso, carrera_id, creditos, descripcion)
            VALUES (?, ?, ?, ?, ?)`,
            [nombre, codigo_curso, carrera_id, creditos, descripcion]
        );

        return resultado.insertId;
    }

    // Actualizar un curso
    static async actualizar(id, datos) {
        const { nombre, codigo_curso, carrera_id, creditos, descripcion } = datos;

        const [resultado] = await conexion.query(
            `UPDATE cursos
            SET nombre = ?, codigo_curso = ?, carrera_id = ?,
                creditos = ?, descripcion = ?
            WHERE id = ?`,
            [nombre, codigo_curso, carrera_id, creditos, descripcion, id]
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
