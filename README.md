BookManager 📚

Sistema de gestión de librería

BookManager es una aplicación web desarrollada como proyecto académico para administrar la información de una librería. Permite gestionar tiendas y títulos mediante operaciones CRUD (Crear, Consultar, Actualizar y Eliminar).

🎯 Objetivo del proyecto

Desarrollar un sistema web que facilite la administración de tiendas y títulos de una librería, aplicando programación orientada a objetos en PHP, conexión a bases de datos MySQL y buenas prácticas de desarrollo.

🛠️ Tecnologías utilizadas

PHP 8: desarrollo de la lógica del sistema.

MySQL: almacenamiento y administración de datos.

PDO: conexión y ejecución segura de consultas SQL.

Bootstrap 5: diseño de la interfaz gráfica.

HTML5 y CSS3: estructura y estilos.

JavaScript: funcionalidades interactivas.

⚙️ Funcionalidades principales

1. Gestión de tiendas

Registrar nuevas tiendas.

Consultar las tiendas registradas.

Editar información de las tiendas.

Eliminar tiendas.

2. Gestión de títulos

Registrar nuevos títulos.

Consultar los libros registrados.

Editar información de los títulos.

Eliminar títulos.

Asociar títulos con sus respectivas editoriales.

3. Panel administrativo

Navegación entre los módulos.

Acceso a las funcionalidades principales.

Interfaz administrativa adaptable mediante Bootstrap 5.

🏗️ Estructura del proyecto

BookManager/
│
├── assets/
│   └── css, js e imágenes
│
├── config/
│   ├── config.php
│   ├── database.php
│   └── database.example.php
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── models/
│   ├── Tienda.php
│   └── Titulo.php
│
├── views/
│   ├── tiendas/
│   │   ├── index.php
│   │   ├── crear.php
│   │   ├── editar.php
│   │   └── eliminar.php
│   │
│   └── titulos/
│       ├── index.php
│       ├── crear.php
│       ├── editar.php
│       └── eliminar.php
│
├── index.php
├── .gitignore
└── README.md
