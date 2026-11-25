const { BASE_PATH } = require('../config/app');
const Curso = require('../models/Curso');
const Carrera = require('../models/Carrera');

// Limpia y valida los datos que vienen del formulario
function validarCurso(body) {
    const datos = {
        nombre: (body.nombre || '').trim(),
        codigo_curso: (body.codigo_curso || '').trim().toUpperCase(),
        creditos: Number(body.creditos),
        carrera_id: Number(body.carrera_id)
    };

    const errores = [];

    if (!datos.nombre) errores.push('El nombre del curso es obligatorio.');
    if (datos.nombre.length > 100) errores.push('El nombre no puede superar 100 caracteres.');
    if (!datos.codigo_curso) errores.push('El código del curso es obligatorio.');
    if (datos.codigo_curso.length > 20) errores.push('El código no puede superar 20 caracteres.');
    if (!Number.isInteger(datos.creditos) || datos.creditos < 1 || datos.creditos > 10) {
        errores.push('La cantidad de créditos debe ser un número entero entre 1 y 10.');
    }
    if (!Number.isInteger(datos.carrera_id) || datos.carrera_id < 1) {
        errores.push('Debe seleccionar una carrera.');
    }

    return { datos, errores };
}

// Traduce errores de MySQL a mensajes para el usuario
function mensajeErrorBD(error) {
    if (error.code === 'ER_DUP_ENTRY') return 'Ya existe un curso con ese código.';
    if (error.code === 'ER_NO_REFERENCED_ROW_2') return 'La carrera seleccionada no existe.';
    return null;
}

// Mostrar todos los cursos
exports.listar = async (req, res) => {
    try {
        const cursos = await Curso.listar();
        res.render('cursos/index', {
            cursos,
            mensaje: req.query.mensaje || null,
            error: req.query.error || null
        });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al mostrar los cursos.' });
    }
};

// Mostrar formulario para crear un curso
exports.formularioCrear = async (req, res) => {
    try {
        const carreras = await Carrera.listar();
        res.render('cursos/form', { curso: {}, carreras, errores: [], modo: 'crear' });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al cargar las carreras.' });
    }
};

// Guardar un curso nuevo
exports.crear = async (req, res) => {
    const { datos, errores } = validarCurso(req.body);

    try {
        if (errores.length === 0) {
            await Curso.crear(datos);
            return res.redirect(BASE_PATH + '/cursos?mensaje=' + encodeURIComponent('Curso creado correctamente.'));
        }
    } catch (error) {
        const mensaje = mensajeErrorBD(error);
        if (!mensaje) {
            console.error(error);
            return res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al registrar el curso.' });
        }
        errores.push(mensaje);
    }

    const carreras = await Carrera.listar();
    res.status(400).render('cursos/form', { curso: datos, carreras, errores, modo: 'crear' });
};

// Mostrar formulario para editar
exports.formularioEditar = async (req, res) => {
    try {
        const curso = await Curso.buscarPorId(req.params.id);

        if (!curso) {
            return res.status(404).render('error', { titulo: 'No encontrado', mensaje: 'Curso no encontrado.' });
        }

        const carreras = await Carrera.listar();
        res.render('cursos/form', { curso, carreras, errores: [], modo: 'editar' });
    } catch (error) {
        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al buscar el curso.' });
    }
};

// Actualizar un curso
exports.actualizar = async (req, res) => {
    const { datos, errores } = validarCurso(req.body);
    datos.id = req.params.id;

    try {
        if (errores.length === 0) {
            const existe = await Curso.buscarPorId(req.params.id);

            if (!existe) {
                return res.status(404).render('error', { titulo: 'No encontrado', mensaje: 'Curso no encontrado.' });
            }

            await Curso.actualizar(req.params.id, datos);
            return res.redirect(BASE_PATH + '/cursos?mensaje=' + encodeURIComponent('Curso actualizado correctamente.'));
        }
    } catch (error) {
        const mensaje = mensajeErrorBD(error);
        if (!mensaje) {
            console.error(error);
            return res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al actualizar el curso.' });
        }
        errores.push(mensaje);
    }

    const carreras = await Carrera.listar();
    res.status(400).render('cursos/form', { curso: datos, carreras, errores, modo: 'editar' });
};

// Eliminar un curso
exports.eliminar = async (req, res) => {
    try {
        const resultado = await Curso.eliminar(req.params.id);

        if (!resultado) {
            return res.status(404).render('error', { titulo: 'No encontrado', mensaje: 'Curso no encontrado.' });
        }

        res.redirect(BASE_PATH + '/cursos?mensaje=' + encodeURIComponent('Curso eliminado correctamente.'));
    } catch (error) {
        // No se puede borrar un curso que ya tiene estudiantes matriculados
        if (error.code === 'ER_ROW_IS_REFERENCED_2') {
            return res.redirect(BASE_PATH + '/cursos?error=' + encodeURIComponent(
                'No se puede eliminar el curso porque tiene estudiantes matriculados.'
            ));
        }

        console.error(error);
        res.status(500).render('error', { titulo: 'Error', mensaje: 'Error al eliminar el curso.' });
    }
};
