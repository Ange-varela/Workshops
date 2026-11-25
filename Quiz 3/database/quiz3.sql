-- Quiz 3 - Estructura y datos iniciales (seed)
-- Se puede ejecutar con "npm run seed" o importar en phpMyAdmin.

CREATE DATABASE IF NOT EXISTS quiz3 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE quiz3;

-- --------------------------------------------------------
-- Carreras
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS carreras (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_carreras_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Cursos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS cursos (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    codigo_curso VARCHAR(20) NOT NULL,
    creditos INT NOT NULL,
    carrera_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_cursos_codigo (codigo_curso),
    KEY fk_cursos_carreras (carrera_id),
    CONSTRAINT fk_cursos_carreras FOREIGN KEY (carrera_id) REFERENCES carreras (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Matrículas
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS matriculas (
    id INT NOT NULL AUTO_INCREMENT,
    cedula VARCHAR(20) NOT NULL,
    nombre_estudiante VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    curso_id INT NOT NULL,
    fecha_matricula TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_matriculas_estudiante_curso (cedula, curso_id),
    KEY fk_matriculas_cursos (curso_id),
    CONSTRAINT fk_matriculas_cursos FOREIGN KEY (curso_id) REFERENCES cursos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Seed de carreras
-- --------------------------------------------------------
INSERT IGNORE INTO carreras (id, nombre) VALUES
(1, 'Ingeniería del Software'),
(2, 'Tecnologías de la Información'),
(3, 'Administración de Empresas'),
(4, 'Contabilidad y Finanzas'),
(5, 'Gestión del Turismo Sostenible');

-- --------------------------------------------------------
-- Seed de cursos (cursos quemados para la matrícula)
-- --------------------------------------------------------
INSERT IGNORE INTO cursos (id, nombre, codigo_curso, creditos, carrera_id) VALUES
(1, 'Programación Web II', 'ISW-613', 4, 1),
(2, 'Sistemas Operativos', 'ISW-612', 4, 1),
(3, 'Bases de Datos I', 'ISW-311', 3, 1),
(4, 'Redes de Computadoras', 'TI-410', 3, 2),
(5, 'Contabilidad General', 'CF-101', 3, 4),
(6, 'Fundamentos de Administración', 'AE-102', 2, 3),
(7, 'Turismo y Desarrollo Sostenible', 'GTS-201', 3, 5);
