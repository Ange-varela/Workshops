require('dotenv').config({ quiet: true });

// Ruta pública desde la que Apache sirve la aplicación (sin "/" al final).
// Ejemplo: /Workshops/Quiz%203  ->  http://isw613.local/Workshops/Quiz%203/
// Vacío = la aplicación se usa directamente en http://localhost:PORT/
const BASE_PATH = (process.env.BASE_PATH || '').replace(/\/+$/, '');

module.exports = { BASE_PATH };
