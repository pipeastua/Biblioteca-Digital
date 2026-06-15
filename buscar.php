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
    $genreFilter = sanitizar($_GET['genre'] ?? '');
    if (!empty($genreFilter) && !in_array($genreFilter, $genres, true)) {
        $genreFilter = '';
    }

    $options = ['all', 'availables', 'notAvailables'];
    $avaiFilterRaw = sanitizar($_GET['availability'] ?? 'all');
    $avaiFilter = in_array($avaiFilterRaw, $options, true) ? $avaiFilterRaw : 'all';

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
            <div class="search-card">
                <h3>Opción 2 - Filtrar por género</h3>
                <div class="form-group">
                    <label for="genre">Seleccione un género</label>
                    <select id="genre" name="genre">
                        <option value="">-- Todos los géneros --</option>
                        <?php foreach ($genres as $g): ?>
                            <option value="<?= sanitizar($g) ?>"
                                <?= (sanitizar($_GET['genre'] ?? '') === $g) ? 'selected' : '' ?>>
                                <?= sanitizar($g) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Opción 2 -->
            <div class="search-card">
                <h3>Opcion 3 - Filtrar por disponibilidad</h3>
                <div class="radio-group">
                    <?php
                    $radioOptions = [
                        'all' => 'Todos',
                        'availables' => 'Disponibles',
                        'notAvailables' => 'No disponibles'
                    ];
                    $selAvai = sanitizar($_GET['availability'] ?? 'todos');
                    foreach ($radioOptions as $val => $d):
                        $checked = ($selAvai === $val) ? 'checked' : '';
                    ?>
                        <label>
                            <input type="radio" name="availability" value="<?= $val ?>"
                                <?= $checked ?>>
                            <?= $d ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Btn's -->
            <div>
                <button type="submit" name="search" value="1" class="btn btn-primary">
                    <lord-icon
                        src="https://cdn.lordicon.com/yudxjmzy.json"
                        trigger="hover"
                        style="width:30px;height:30px">
                    </lord-icon>
                    Filtrar
                </button>
                <a href="buscar.php" class="btn btn-secondary">
                    <lord-icon
                        src="https://cdn.lordicon.com/egqwwrlq.json"
                        trigger="hover"
                        style="width:30px;height:30px">
                    </lord-icon>
                    Limpiar filtros
                </a>
            </div>
        </form>

        <!-- Resultados -->
        <?php if ($searchin): ?>

            <div class="results-header">
                <span>Resultados</span>
                <span class="results-count">
                    <?= $totalResults ?> book <?= $totalResults !== 1 ? 'y' : '' ?> found <?= $totalResults !== 1 ? 'y' : '' ?>
                </span>
            </div>

            <?php if ($totalResults > 0): ?>
                <div class="table-wrapper">
                    <table class="table-books">
                        <thead>
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Género</th>
                                <th>Año</th>
                                <th>Páginas</th>
                                <th>Estado</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $book):
                                $statusClass = $book['available'] ? 'badge-available' : 'badge-not-available';
                                $statusTxt = $book['available'] ? 'Disponible' : 'No disponible';
                            ?>
                                <tr>
                                    <td data-label="ISBN"><code><?= sanitizar($book['isbn']) ?></code></td>
                                    <td data-label="Título" class="title-cell"><?= sanitizar($book['title']) ?></td>
                                    <td data-label="Autor"><?= sanitizar($book['autor']) ?></td>
                                    <td data-label="Género">
                                        <span class="badge-genre"><?= sanitizar($book['genre']) ?></span>
                                    </td>
                                    <td data-label="Año"><?= (int)$book['year'] ?></td>
                                    <td data-label="Páginas"><?= number_format((int)$book['pages']) ?></td>
                                    <td data-label="Estado">
                                        <span class="badge <?= $statusClass ?>"><?= $statusTxt ?></span>
                                    </td>
                                    <td data-label="Stock"><?= (int)$book['stock'] ?>-</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <p>
                    Mostrando <?= $totalResults ?> de <?= $totalLibrary ?> libros.
                    Usando <?= count($genres) ?> géneros disponibles.
                </p>

            <?php else: ?>
                <div class="empty-state">
                    <span class="empty-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/yudxjmzy.json"
                            trigger="morph"
                            state="morph-cross"
                            style="width:30px;height:30px">
                        </lord-icon>
                        <p>No hay resultados que coincidan con los filtros.</p>
                        <a href="buscar.php" class="btn btn-secondary">
                            <lord-icon
                                src="https://cdn.lordicon.com/egqwwrlq.json"
                                trigger="hover"
                                style="width:30px;height:30px">
                            </lord-icon>
                            Limpiar filtros
                        </a>
                    </span>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-info">
                Seleccione los filtros y pulse <strong>Filtrar</strong>.
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <p>&copy; 2026 BiblioTECH · Sistema de Gestión Bibliotecaria</p>
    </footer>

</html>