# BiblioTECH - Sistema de Gestion de Biblioteca Digital

BiblioTECH es un sistema web desarrollado en PHP para registrar, buscar, filtrar y administrar libros dentro de una biblioteca digital. El proyecto utiliza sesiones para almacenar los datos mientras el usuario navega por el sistema.

## Caracteristicas

- Dashboard principal con listado de libros registrados.
- Registro de libros mediante formulario POST.
- Validacion de datos del formulario.
- Sanitizacion de entradas con htmlspecialchars().
- Almacenamiento de libros en una variable de sesion.
- Catalogo inicial con 3 libros de ejemplo.
- Busqueda de libros por titulo.
- Filtro por genero.
- Filtro por disponibilidad.
- Estadisticas generales del inventario.
- Cambio de disponibilidad desde la tabla principal.
- Diseno personalizado con CSS.
- Interfaz responsiva y organizada.

## Estructura del proyecto

EXAMEN_MOYA_FELIPE/
├── index.php
├── registrar.php
├── procesar_registro.php
├── buscar.php
├── estadisticas.php
├── salir.php
├── includes/
│   └── funciones.php
└── css/
    └── styles.css

## Requisitos

Para ejecutar el proyecto se necesita:

- XAMPP, WAMP, Laragon o cualquier servidor local con PHP.
- Navegador web.
- PHP con soporte para sesiones habilitado.

## Como ejecutar el sistema

1. Copiar la carpeta del proyecto dentro del directorio htdocs de XAMPP.

   Ejemplo:

   C:\xampp\htdocs\EXAMEN_MOYA_FELIPE

2. Iniciar Apache desde el panel de control de XAMPP.

3. Abrir el navegador y entrar a:

   http://localhost/EXAMEN_MOYA_FELIPE/index.php

4. Desde el menu principal se puede acceder a:
   - Registrar libro
   - Buscar/filtrar libros
   - Estadisticas
   - Cambiar disponibilidad de libros

## Uso del sistema

### Registrar libro

En la pagina de registro se ingresan los datos del libro:

- ISBN
- Titulo
- Autor
- Genero
- Ano de publicacion
- Numero de paginas
- Stock en inventario
- Disponibilidad

El sistema valida los datos antes de guardar el libro. Si hay errores, se muestran mensajes especificos en pantalla.

### Buscar y filtrar libros

La pagina de busqueda permite filtrar libros por:

- Parte del titulo
- Genero
- Disponibilidad

Los resultados se muestran en una tabla junto con la cantidad de libros encontrados.

### Estadisticas

La pagina de estadisticas muestra informacion general del catalogo, como:

- Total de libros
- Libros disponibles
- Libros no disponibles
- Inventario total
- Libro mas antiguo
- Libro mas reciente
- Genero mas popular

### Cambiar disponibilidad

Desde la tabla principal se puede cambiar el estado de disponibilidad de cada libro. El sistema actualiza el estado y muestra una confirmacion.

## Tecnologias utilizadas

- PHP
- HTML5
- CSS3
- Sesiones de PHP
- Formularios GET y POST

## Autor

Felipe Moya Astúa

## Curso

Programacion IV

## Nombre del proyecto

Sistema de Gestion de Biblioteca Digital
