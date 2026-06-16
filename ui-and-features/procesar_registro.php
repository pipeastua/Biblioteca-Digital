<?php

include 'includes/funciones.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

iniciarSesionApp();
inicializarLibros();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registrar.php');
    exit;
}

// Sanitizar data
$isbn = sanitizar($_POST['isbn'] ?? '');
$title = sanitizar($_POST['title'] ?? '');
$autor = sanitizar($_POST['autor'] ?? '');
$genre = sanitizar($_POST['genre'] ?? '');
$rawYear = trim($_POST['year'] ?? '');
$rawPages = trim($_POST['pages'] ?? '');
$rawStock = trim($_POST['stock'] ?? '');
$available = isset($_POST['available']) && $_POST['available'] === '1';

// Numeros 
$year = (int)$rawYear;
$pages = (int)$rawPages;
$stock = (int)$rawStock;

// Validaciones
$errors = [];
$genres = getGenres();

// ISBN
if (empty($isbn)) {
    $errors['isbn'] = 'El ISBN es obligatorio.';
} elseif (!ctype_digit($isbn)) {
    $errors['isbn'] = 'El ISBN solo debe contener números.';
} elseif (strlen($isbn) > MAX_ISBN) {
    $errors['isbn'] = 'El ISBN no puede ser mayor a ' . MAX_ISBN . ' caracteres.';
} elseif (isbnExist($isbn, $_SESSION['books'])) {
    $errors['isbn'] = 'El ISBN "' . $isbn . '" ya  está registrado.';
}

// Titulo
if (empty($title)) {
    $errors['title'] = 'El título es obligatorio';
}

// Autor
if (empty($autor)) {
    $errors['autor'] = 'El autor es obligatorio.';
} elseif (strlen($autor) < MIN_AUTOR) {
    $errors['autor'] = 'El nombre del autor debe ser de almenos ' . MIN_AUTOR . ' caracteres';
}

// Género
if (empty($genre)) {
    $errors['genre'] = 'Debe seleccionar un género.';
} elseif (!in_array($genre, $genres, true)) {
    $errors['genre'] = 'El género seleccionado no se encuentra actualmente en la lista... Elije otro.';
}

// Año
if ($rawYear === '' || !is_numeric($rawYear)) {
    $errors['year'] = 'El año de publicación es obligatorio.';
} elseif ($year < MIN_YEAR || $year > MAX_YEAR) {
    $errors['year'] = 'El año debe estar entre ' . MIN_YEAR . ' y ' . MAX_YEAR . '.';
}

// Páginas
if ($rawPages === '' || !is_numeric($rawPages)) {
    $errors['pages'] = "El número de páginas es obligatorio.";
} elseif ($pages < MIN_PAGES || $pages > MAX_PAGES) {
    $errors['pages'] = 'Las páginas deben estar entre ' . MIN_PAGES . ' y ' . MAX_PAGES . '.';
}

// Stock
if ($rawStock === '' || !is_numeric($rawStock)) {
    $errors['stock'] = 'La stock es obligatorio.';
} elseif ($stock < MIN_AMOUNT) {
    $errors['stock'] = 'El stock debe ser de al menos ' . MIN_AMOUNT . '.';
}

// Guarda la sesión y redirige al forms en caso de errores
if (!empty($errors)) {
    $_SESSION['formErrors'] = $errors;
    $_SESSION['formData'] = [
        'isbn' => $isbn,
        'title' => $title,
        'autor' => $autor,
        'genre' => $genre,
        'year' => $year,
        'pages' => $pages,
        'stock' => $stock,
        'available' => $available
    ];
    header('Location: registrar.php');
    exit;
}

// Sin errores
$newBook = [
    'isbn' => $isbn,
    'title' => $title,
    'autor' => $autor,
    'genre' => $genre,
    'year' => $year,
    'pages' => $pages,
    'available' => $available,
    'stock' => $stock
];

array_push($_SESSION['books'], $newBook);

$_SESSION['successMsg'] = 'Libro agregado correctamente. Ya aparece en el catalogo.';

header('Location: index.php');
exit;
