
const express = require('express');
const path = require('path');
require('dotenv').config();

const app = express();
const cursoRoutes = require('./routes/cursoRoutes');

// Configurar EJS
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Leer datos de formularios
app.use(express.urlencoded({ extended: true }));

// Archivos publicos
app.use(express.static(path.join(__dirname, 'public')));

// Pagina principal
app.get('/', (req, res) => {
    res.redirect('/cursos');
});

// Rutas de cursos
app.use('/cursos', cursoRoutes);

// Iniciar servidor
const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
    console.log(`Servidor iniciado en http://localhost:${PORT}`);
});
