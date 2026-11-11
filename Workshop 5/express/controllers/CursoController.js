
const Curso = require('../models/Curso');

// Mostrar todos los cursos
exports.listar = async (req, res) => {
    try {
        const cursos = await Curso.listar();
        res.render('cursos/index', { cursos });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al mostrar los cursos');
    }
};

// Mostrar formulario para crear un curso
exports.formularioCrear = (req, res) => {
    res.render('cursos/crear');
};

// Guardar un curso nuevo
exports.crear = async (req, res) => {
    try {
        await Curso.crear(req.body);
        res.redirect('/cursos');
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al registrar el curso');
    }
};

// Mostrar formulario para editar
exports.formularioEditar = async (req, res) => {
    try {
        const curso = await Curso.buscarPorId(req.params.id);

        if (!curso) {
            return res.status(404).send('Curso no encontrado');
        }

        res.render('cursos/editar', { curso });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al buscar el curso');
    }
};

// Actualizar un curso
exports.actualizar = async (req, res) => {
    try {
        const resultado = await Curso.actualizar(req.params.id, req.body);

        if (!resultado) {
            return res.status(404).send('Curso no encontrado');
        }

        res.redirect('/cursos');
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al actualizar el curso');
    }
};

// Eliminar un curso
exports.eliminar = async (req, res) => {
    try {
        const resultado = await Curso.eliminar(req.params.id);

        if (!resultado) {
            return res.status(404).send('Curso no encontrado');
        }

        res.redirect('/cursos');
    } catch (error) {
        console.error(error);
        res.status(500).send('Error al eliminar el curso');
    }
};
