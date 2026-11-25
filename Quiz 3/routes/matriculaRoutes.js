const express = require('express');
const router = express.Router();

const MatriculaController = require('../controllers/MatriculaController');

// Página principal: formulario de matrícula
router.get('/', MatriculaController.formulario);

// Guardar matrícula
router.post('/matricular', MatriculaController.matricular);

// Listado y detalle de matrículas
router.get('/matriculas', MatriculaController.listar);
router.get('/matriculas/:id', MatriculaController.detalle);

module.exports = router;
