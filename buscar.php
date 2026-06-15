<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$duration = 604800;
ini_set('session.gc_maxlifetime', $duration);
session_set_cookie_params([
    'lifetime' => $duration,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include 'includes/funciones.php';

if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = bookStock();
}

$genres = getGenres();
$books = $_SESSION['books'];
$stats = stats($books);

$titleSearch = '';
$genreFilter = '';
$avaiFilter = 'all';
$searchin = false;
$results = [];

if (isset($_GET['find'])) {
    $searchin = true;

    $titleSearch = sanitizar($_GET['title'] ?? '');
    $genreFilter = sanitizar($_GET['genero'] ?? '');
    if (!empty($genreFilter) && !in_array($genreFilter, $genres, true)) {
        $genreFilter = '';
    }

    $options = ['all', 'availables', 'notAvailables'];
    $avaiFilterRaw = sanitizar($_GET['availability'] ?? 'all');
    $avaiFilter = in_array($avaiFilterRaw, $options, true) ? $avaiFilterRaw : 'todos';

    $results = bookFinder($books, $titleSearch, $genreFilter, $avaiFilter);
}

$totalResults = count($results);
$totalLibrary = count($books);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTECH</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body>

    <header class="header">
        <div class="header-content">
            <lord-icon
                src="https://cdn.lordicon.com/zbtbhzsg.json"
                trigger="morph"
                state="morph-open"
                style="width:65px;height:65px">
            </lord-icon>
            <h1>BiblioTECH</h1>
        </div>
    </header>

    <main class="container">

        <div class="page-title">
            <h2>Filtrar libros</h2>
            <span class="badge"><?= $totalLibrary ?> en el catálogo</span>
        </div>

        <form method="GET" action="buscar.php">
            <!-- Opción 1 -->
            <div class="search-card">
                <h3>Opción 1 - Filtrar por título </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Parte el título</label>
                        <input type="text" id="title" name="title" placeholder="Ej: La Odisea, La epopeya de Gilgamesh, La Ilíada..." value="<?= sanitizar($_GET['title'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Opción 2 -->
            <div>

            </div>
        </form>

    </main>

</html>