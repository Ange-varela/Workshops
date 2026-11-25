# Quiz 3 – CRUD de cursos y matrícula de estudiantes

Aplicación web hecha con **Express (Node.js)** siguiendo el patrón **MVC**, con vistas en **EJS**, estilos con **Bootstrap 5** y base de datos **MySQL/MariaDB** (XAMPP).

## Funcionalidades

- **Matrícula (página principal `/`)**: formulario con los datos del estudiante (cédula, nombre, correo) y el listado de cursos cargados por seed. Se selecciona un curso, se presiona **Matricular**, se guarda el registro y se muestra el comprobante con los datos de la matrícula.
- **Listado de matrículas (`/matriculas`)**: todas las matrículas registradas con enlace al detalle.
- **CRUD de cursos (`/cursos`)**: crear, listar, editar y eliminar cursos con Nombre del curso, Código de curso, Cantidad de créditos y Carrera.
- **Seed de carreras y cursos**: `npm run seed` crea la base de datos, las tablas y carga los datos iniciales.

Validaciones incluidas: campos obligatorios, créditos entre 1 y 10, código de curso único, correo válido, un estudiante no puede matricularse dos veces en el mismo curso y no se puede eliminar un curso con estudiantes matriculados.

## Estructura (MVC)

```
Quiz 3/
├── .htaccess               # Apache pasa las visitas a Express
├── app.js                  # Configuración de Express y rutas principales
├── config/
│   ├── app.js              # Ruta base pública (BASE_PATH)
│   └── database.js         # Pool de conexión a MySQL
├── models/                 # Modelos (acceso a datos)
│   ├── Carrera.js
│   ├── Curso.js
│   └── Matricula.js
├── controllers/            # Controladores (lógica)
│   ├── CursoController.js
│   └── MatriculaController.js
├── routes/                 # Rutas
│   ├── cursoRoutes.js
│   └── matriculaRoutes.js
├── views/                  # Vistas EJS
│   ├── partials/
│   ├── cursos/
│   └── matriculas/
├── public/css/estilos.css
└── database/
    ├── quiz3.sql           # Estructura + seed (carreras y cursos)
    └── seed.js             # Ejecuta quiz3.sql
```

## Instalación

La aplicación se abre en **http://isw613.local/Workshops/Quiz%203/**, igual que los demás workshops. Apache (XAMPP) recibe la visita y, por medio del `.htaccess` de esta carpeta, la pasa a Express, que corre en el puerto 3000 solo de forma interna.

1. **Activar el proxy en Apache (una sola vez).** En `C:\xampp\apache\conf\httpd.conf` hay que verificar que estas líneas **no** tengan `#` al inicio, y reiniciar Apache desde el panel de XAMPP:
   ```
   LoadModule proxy_module modules/mod_proxy.so
   LoadModule proxy_http_module modules/mod_proxy_http.so
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
2. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
3. Entrar a la carpeta del proyecto e instalar las dependencias:
   ```bash
   cd "C:\xampp\htdocs\Workshops\Quiz 3"
   npm install
   ```
4. Crear el archivo `.env` a partir del ejemplo:
   ```bash
   copy .env.example .env
   ```
5. Crear la base de datos y cargar el seed:
   ```bash
   npm run seed
   ```
   (También se puede importar `database/quiz3.sql` desde phpMyAdmin.)
6. Iniciar la aplicación y dejar la terminal abierta:
   ```bash
   npm start
   ```
7. Abrir <http://isw613.local/Workshops/Quiz%203/>.

> Para usarla sin Apache, se deja `BASE_PATH` vacío en `.env` y se abre <http://localhost:3000>.

## Rutas

Las rutas son relativas a `/Workshops/Quiz%203`.

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/` | Formulario de matrícula |
| POST | `/matricular` | Guarda la matrícula |
| GET | `/matriculas` | Lista de matrículas |
| GET | `/matriculas/:id` | Datos de una matrícula |
| GET | `/cursos` | Lista de cursos |
| GET / POST | `/cursos/crear` | Formulario y guardado de curso |
| GET | `/cursos/editar/:id` | Formulario de edición |
| POST | `/cursos/actualizar/:id` | Actualiza un curso |
| POST | `/cursos/eliminar/:id` | Elimina un curso |
