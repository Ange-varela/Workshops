const express = require('express');
const router = express.Router();

const CursoController = require('../controllers/CursoController');

// Listar cursos
router.get('/', CursoController.listar);

// Crear curso
router.get('/crear', CursoController.formularioCrear);
router.post('/crear', CursoController.crear);

// Editar curso
router.get('/editar/:id', CursoController.formularioEditar);
router.post('/actualizar/:id', CursoController.actualizar);

// Eliminar curso
router.post('/eliminar/:id', CursoController.eliminar);

module.exports = router;
