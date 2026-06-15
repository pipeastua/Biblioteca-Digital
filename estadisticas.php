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

$books = $_SESSION['books'];
$stats = stats($books);

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
            <h2>Estadísticas</h2>
            <span class="badge"><?= $stats['total'] ?> Libros en total </span>
        </div>

        <?php if ($stats['total'] === 0): ?>
            <div class="alert alert-info">
                No hay libros agregados.
                <a href="registrar.php" class="btn btn-primary">Agregar libro</a>
            </div>
        <?php else: ?>

            <!-- CARDS -->
            <div class="stats-grid">

                <div class="stat-card">
                    <span class="stat-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/zxqcyyit.json"
                            trigger="hover"
                            state="hover-unroll"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </span>
                    <span class="stat-value"><?= $stats['total'] ?></span>
                    <span class="stat-desc">Total de libros</span>
                </div>

                <div class="stat-card">
                    <span class="stat-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/zxqcyyit.json"
                            trigger="morph"
                            state="morph-checked"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </span>
                    <span class="stat-value"><?= $stats['availables'] ?></span>
                    <span class="stat-desc">Libros disponibles</span>
                </div>

                <div class="stat-card">
                    <span class="stat-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/zxqcyyit.json"
                            trigger="morph"
                            state="morph-minus"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </span>
                    <span class="stat-value"><?= $stats['notAvailables'] ?></span>
                    <span class="stat-desc">No disponibles</span>
                </div>

                <div class="stat-card">
                    <span class="stat-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/tbabdzcy.json"
                            trigger="hover"
                            colors="primary:#121331,secondary:#ffc738"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </span>
                    <span class="stat-value"><?= $stats['totalInventory'] ?></span>
                    <span class="stat-desc">Inventario total</span>
                </div>

                <div class="stat-card">
                    <span class="stat-icon">
                        <lord-icon
                            src="https://cdn.lordicon.com/uihwbzln.json"
                            trigger="hover"
                            colors="primary:#121331,secondary:#ffc738,tertiary:#ffc738"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </span>
                    <span class="stat-value"><?= sanitizar((string)$stats['popularGenre']) ?></span>
                    <span class="stat-desc">es el género favorito!</span>
                </div>
            <?php endif; ?>
            </div>

            <!-- Libros por antigueadad -->
            <div class="form-card">
                <h3>Libro más antiguo</h3>
                <?php if ($stats['oldestBook'] !== 'N/A' && $stats['oldestBook'] !== null): ?>
                    <p><?= sanitizar($stats['oldestBook']) ?></p>
                    <p>Publicado en <strong><?= $stats['minYear'] ?></strong></p>
                <?php else: ?>
                    <p>N/A</p>
                <?php endif; ?>
            </div>

            <div class="form-card">
                <h3>Libro más reciente</h3>
                <?php if ($stats['newestBook'] !== 'N/A' && $stats['newestBook'] !== null): ?>
                    <p><?= sanitizar($stats['newestBook']) ?></p>
                    <p>Publicado en <strong><?= $stats['maxYear'] ?></strong></p>
                <?php else: ?>
                    <p>N/A</p>
                <?php endif; ?>
            </div>

            <div class="form-card">
                <h3>Géneros</h3>
                <?php if (!empty($stats['genres'])): ?>
                    <table class="genre-table">
                        <thead>
                            <tr>
                                <th>Género</th>
                                <th>Cantidad de libros</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($stats['genres'] as $genre => $amount):
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge-genre"><?= sanitizar($genre) ?></span>
                                    </td>
                                    <td><strong><?= $amount ?></strong></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Sin datos de géneros.</p>
                <?php endif; ?>
            </div>

            <div class="form-card">
                <h3>Resumen</h3>
                <?php
                $summary = [
                    "Total de libros: <strong>{$stats['total']}</strong>",
                    "Libros disponibles: <strong>{$stats['availables']}</strong>",
                    "Libros no disponibles: <strong>{$stats['notAvailables']}</strong>",
                    "Libro más antiguo: <strong>" . sanitizar((string)$stats['oldestBook']) . "</strong>",
                    "Libro más reciente: <strong>" . sanitizar((string)$stats['newestBook']) . "</strong>",
                    "Género favorito: <strong>" . sanitizar((string)$stats['popularGenre']) . "</strong>",
                    "Stock general: <strong>{$stats['totalInventory']}</strong>",
                ];

                $i = 0;
                while ($i < count($summary)) {
                    echo "<p>" . ($i + 1) . "." . $summary[$i] . "</p>";
                    $i++;
                }
                ?>
            </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 BiblioTECH · Sistema de Gestión Bibliotecaria</p>
    </footer>

</body>

</html>