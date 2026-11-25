const { BASE_PATH } = require('../config/app');
const Matricula = require('../models/Matricula');
const Curso = require('../models/Curso');

const CORREO_VALIDO = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Limpia y valida los datos del formulario de matrícula
function validarMatricula(body) {
    const datos = {
        cedula: (body.cedula || '').trim(),
        nombre_estudiante: (body.nombre_estudiante || '').trim(),
        correo: (body.correo || '').trim().toLowerCase(),
        curso_id: Number(body.curso_id)
    };

    const errores = [];

    if (!datos.cedula) errores.push('La cédula es obligatoria.');
    if (datos.cedula.length > 20) errores.push('La cédula no puede superar 20 caracteres.');
    if (!datos.nombre_estudiante) errores.push('El nombre del estudiante es obligatorio.');
    if (datos.nombre_estudiante.length > 100) errores.push('El nombre no puede superar 100 caracteres.');
    if (!CORREO_VALIDO.test(datos.correo)) errores.push('Debe ingresar un correo válido.');
    if (!Number.isInteger(datos.curso_id) || datos.curso_id < 1) {
        errores.push('Debe seleccionar un curso de la lista.');
    }

    return { datos, errores };
}

// Página principal: formulario de matrícula con el listado de cursos
exports.formulario = async (req, res) => {
    try {
        const cursos = await Curso.listar();
        res.render('matriculas/form', { cursos, datos: {}, errores: [] });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al cargar los cursos.' });
    }
};

// Guardar la matrícula y mostrar el comprobante
exports.matricular = async (req, res) => {
    const { datos, errores } = validarMatricula(req.body);

    try {
        if (errores.length === 0) {
            const id = await Matricula.crear(datos);
            return res.redirect(`${BASE_PATH}/matriculas/${id}`);
        }
    } catch (error) {
        if (error.code === 'ER_DUP_ENTRY') {
            errores.push('Este estudiante ya está matriculado en el curso seleccionado.');
        } else if (error.code === 'ER_NO_REFERENCED_ROW_2') {
            errores.push('El curso seleccionado no existe.');
        } else {
            console.error(error);
            return res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al registrar la matrícula.' });
        }
    }

    const cursos = await Curso.listar();
    res.status(400).render('matriculas/form', { cursos, datos, errores });
};

// Mostrar los datos de una matrícula registrada
exports.detalle = async (req, res) => {
    try {
        const matricula = await Matricula.buscarPorId(req.params.id);

        if (!matricula) {
            return res.status(404).render('error', { titulo: 'No encontrado', mensaje: 'Matrícula no encontrada.' });
        }

        res.render('matriculas/detalle', { matricula });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al buscar la matrícula.' });
    }
};

// Listado de todas las matrículas
exports.listar = async (req, res) => {
    try {
        const matriculas = await Matricula.listar();
        res.render('matriculas/index', { matriculas });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al mostrar las matrículas.' });
    }
};
