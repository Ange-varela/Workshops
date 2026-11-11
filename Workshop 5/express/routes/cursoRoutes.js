
const express = require('express');
const router = express.Router();

const CursoController = require('../controllers/CursoController');

// Mostrar cursos
router.get('/', CursoController.listar);

// Mostrar formulario para crear
router.get('/crear', CursoController.formularioCrear);

// Guardar curso
router.post('/crear', CursoController.crear);

// Mostrar formulario para editar
router.get('/editar/:id', CursoController.formularioEditar);

// Actualizar curso
router.post('/actualizar/:id', CursoController.actualizar);

// Eliminar curso
router.post('/eliminar/:id', CursoController.eliminar);

module.exports = router;
