const express = require('express');
const path = require('path');
require('dotenv').config({ quiet: true });

const { BASE_PATH } = require('./config/app');

const app = express();

const matriculaRoutes = require('./routes/matriculaRoutes');
const cursoRoutes = require('./routes/cursoRoutes');

// Motor de vistas EJS
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Leer datos de formularios
app.use(express.urlencoded({ extended: true }));

// Archivos públicos
app.use(express.static(path.join(__dirname, 'public')));

// Datos disponibles en todas las vistas:
// base = ruta pública (para armar los enlaces), rutaActual = para marcar el menú activo
app.use((req, res, next) => {
    res.locals.base = BASE_PATH;
    res.locals.rutaActual = req.path;
    next();
});

// Página principal: formulario de matrícula
app.use('/', matriculaRoutes);

// CRUD de cursos
app.use('/cursos', cursoRoutes);

// Página no encontrada
app.use((req, res) => {
    res.status(404).render('error', {
        titulo: 'Página no encontrada',
        mensaje: 'La página que busca no existe.'
    });
});

const PORT = process.env.PORT || 3000;

// Solo escucha en la máquina local: Apache (XAMPP) es quien recibe las visitas
app.listen(PORT, '127.0.0.1', () => {
    console.log(`Servidor Node escuchando en el puerto ${PORT}`);
    console.log(`Abrir: ${process.env.APP_URL || `http://localhost:${PORT}/`}`);
});
