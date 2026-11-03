# Workshop 4 - Gestion de cursos con MVC

## Descripcion
Aplicacion web desarrollada en PHP y MySQL para gestionar usuarios, perfiles y cursos. Este proyecto es una continuacion del Workshop 3, reorganizado mediante la arquitectura MVC (Modelo, Vista y Controlador) para separar la logica de la aplicacion, las consultas a la base de datos y la interfaz del usuario.

## Funcionalidades
- Registro de usuarios con nombre de usuario, contraseña y provincia.
- Inicio de sesion y validacion de credenciales.
- Menu de navegacion con acceso al perfil, cursos y cierre de sesion.
- Visualizacion y actualizacion del perfil del usuario.
- Cambio de contraseña y fotografia de perfil.
- Listado de cursos con nombre, codigo, ID de carrera, creditos y descripcion.
- Creacion, consulta, edicion y eliminacion de cursos (CRUD).
- Validacion de datos y codigos de curso duplicados.
- Proteccion de paginas mediante sesiones PHP.
- Cierre de sesion y control de inactividad.
- Proteccion CSRF en los formularios de creacion, edicion y eliminacion de cursos.
- Consultas a la base de datos mediante PDO y sentencias preparadas.

## Arquitectura MVC
El proyecto utiliza la arquitectura Modelo-Vista-Controlador para organizar el codigo.

- **Modelo:** realiza las consultas y operaciones con la base de datos.
- **Vista:** muestra los formularios, tablas y contenido al usuario.
- **Controlador:** recibe las solicitudes y coordina las operaciones entre los modelos y las vistas.

## Estructura del proyecto

```text
Workshop 4/
├── app/
│   ├── Controllers/
│   ├── Core/
│   │   └── Database.php
│   ├── Models/
│   └── Views/
│       ├── auth/
│       ├── cursos/
│       ├── layouts/
│       └── usuarios/
├── public/
├── utils/
├── index.php
├── registro.php
├── workshop4.sql
└── README.md
```

## Tecnologias utilizadas
- PHP
- MySQL
- HTML
- JavaScript
- Bootstrap 4
- PDO
- XAMPP

## Base de datos
La base de datos se llama `workshop4` y contiene las siguientes tablas:

- `usuarios`
- `provincias`
- `cursos`

El archivo `workshop4.sql` permite importar la estructura y los datos de la base de datos.

## Ejecucion
1. Iniciar Apache y MySQL desde XAMPP.
2. Abrir phpMyAdmin.
3. Importar el archivo `workshop4.sql`.
4. Verificar la conexion MySQL en `app/Core/Database.php`.
5. Abrir la siguiente direccion en el navegador:

   `http://localhost/Workshops/Workshop%204/public/login.php`

6. Registrar un usuario o iniciar sesion con una cuenta existente.
7. Acceder al perfil y a la seccion Cursos para realizar las operaciones disponibles.

## Seguridad
- Las contraseñas se almacenan mediante `password_hash()` y se verifican con `password_verify()`.
- Se utilizan sesiones PHP para controlar el acceso.
- Se utilizan consultas preparadas con PDO.
- Los formularios de creacion, edicion y eliminacion de cursos incluyen proteccion CSRF.
- Se controla la inactividad de las sesiones.

## Autor
Angelica Varela Vargas

## Curso
ISW-613 - Programacion de Ambientes Web I
