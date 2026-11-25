const conexion = require('../config/database');

class Carrera {

    // Obtener todas las carreras
    static async listar() {
        const [carreras] = await conexion.query(
            'SELECT id, nombre FROM carreras ORDER BY nombre'
        );

        return carreras;
    }
}

module.exports = Carrera;
