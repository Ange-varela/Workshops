// Crea la base de datos, las tablas y carga los datos iniciales (carreras y cursos).
// Uso: npm run seed

const fs = require('fs');
const path = require('path');
const mysql = require('mysql2/promise');
require('dotenv').config({ path: path.join(__dirname, '..', '.env'), quiet: true });

async function ejecutarSeed() {
    const nombreBD = process.env.DB_NAME || 'quiz3';

    // Se usa el nombre de la base definido en .env en lugar de "quiz3"
    const sql = fs
        .readFileSync(path.join(__dirname, 'quiz3.sql'), 'utf8')
        .replace(/\bquiz3\b(?=\s+CHARACTER SET|;)/g, `\`${nombreBD}\``);

    const conexion = await mysql.createConnection({
        host: process.env.DB_HOST,
        port: process.env.DB_PORT,
        user: process.env.DB_USER,
        password: process.env.DB_PASSWORD,
        multipleStatements: true
    });

    try {
        await conexion.query(sql);
        console.log(`Seed ejecutado correctamente en la base "${nombreBD}".`);
    } finally {
        await conexion.end();
    }
}

ejecutarSeed().catch((error) => {
    console.error('Error al ejecutar el seed:', error.message);
    process.exit(1);
});
