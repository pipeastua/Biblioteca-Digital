<?php

include 'includes/funciones.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

iniciarSesionApp();
inicializarLibros();

$changedMsg = '';
$successMsg = $_SESSION['successMsg'] ?? '';
unset($_SESSION['successMsg']);

if (isset($_GET['accion'], $_GET['isbn']) && $_GET['accion'] === 'changeAvai' && !empty($_GET['isbn'])) {

    $isbnPointed = sanitizar($_GET['isbn']);
    $found = false;

    foreach ($_SESSION['books'] as $key => $book) {
        if ($book['isbn'] === $isbnPointed) {

            $_SESSION['books'][$key]['available'] = !$_SESSION['books'][$key]['available'];
            $newState = $_SESSION['books'][$key]['available'] ? 'Disponible' : 'No disponible';
            $changedMsg = "Estado del libro <strong>\"" . sanitizar($book['title']) . "\"</strong> cambiado a: <strong> {$newState} </strong>";
            $found = true;
            break;
        }
    }
    unset($book);

    if (!$found) {
        $changedMsg = "ISBN no encontrado";
    }
}

$books = $_SESSION['books'];
$stats  = stats($books);
$total  = count($books);
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
            <lord-icon
                src="https://cdn.lordicon.com/pgirtdfe.json"
                trigger="morph"
                state="morph-neighbourhood"
                style="width:60px;height:60px">
            </lord-icon>

            <span class="bagde"> <?= $total ?> libro<?= $total !== 1 ? 's' : '' ?></span>
        </div>

        <?php if (!empty($changedMsg)): ?>
            <div class="alert alert-success">

                <lord-icon
                    src="https://cdn.lordicon.com/pxixoqxa.json"
                    trigger="in"
                    delay="1500"
                    state="in-reveal"
                    style="width:250px;height:250px">
                </lord-icon>

                <?= $changedMsg ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success">
                <strong><?= sanitizar($successMsg) ?></strong>
            </div>
        <?php endif; ?>

        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-num"><?= $stats['total'] ?></span>
                <span class="stat-label">Total libros</span>
            </div>
            <div class="stat-item">
                <span class="stat-num"><?= $stats['availables'] ?></span>
                <span class="stat-label">Disponibles</span>
            </div>
            <div class="stat-item">
                <span class="stat-num"><?= $stats['notAvailables'] ?></span>
                <span class="stat-label">No Disponibles</span>
            </div>
            <div class="stat-item">
                <span class="stat-num"><?= $stats['totalInventory'] ?></span>
                <span class="stat-label">Inventario Total</span>
            </div>
        </div>

        <!--Quick access-->
        <div class="action-bar">
            <a href="registrar.php" class="btn btn-primary">Agregar</a>
            <a href="buscar.php" class="btn btn-secondary">Buscar</a>
            <a href="estadisticas.php" class="btn btn-secondary">Estadisticas</a>
        </div>

        <!--Book table-->
        <?php if ($total > 0): ?>
            <div class="table-wrapper">
                <table class="book-table">
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
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($books as $book):
                            $classStatus = $book['available'] ? 'bagde-available' : 'badge-not-available';
                            $txtState = $book['available'] ? 'Disponible' : 'No disponible';
                        ?>
                            <tr>
                                <td data-label="ISBN">
                                    <code><?= sanitizar($book['isbn']) ?></code>
                                </td>
                                <td data-label="Titulo" class="titulo-cell">
                                    <?= sanitizar($book['title']) ?>
                                </td>
                                <td data-label>
                                    <?= sanitizar($book['autor']) ?>
                                </td>
                                <td data-label="Genero">
                                    <span class="badge-genero"><?= sanitizar($book['genre']) ?></span>
                                </td>
                                <td data-label="Año"><?= (int)$book['year'] ?></td>
                                <td data-label="Páginas"><?= number_format((int)$book['pages']) ?></td>
                                <td data-label="Estado">
                                    <span class="badge <?= $classStatus ?>"><?= $txtState ?></span>
                                </td>
                                <td data-label="Cantidad"><?= (int)$book['stock'] ?></td>
                                <td data-label="Accion">
                                    <a href="index.php?accion=changeAvai&amp;isbn=<?= urlencode($book['isbn']) ?>"
                                        class="btn btn-toggle"
                                        onclick="return confirm ('Desea cambiar la disponibilidad de este libro?')">
                                        <lord-icon
                                            src="https://cdn.lordicon.com/veoztjjj.json"
                                            trigger="hover"
                                            style="width:30px;height:30px">
                                        </lord-icon>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <div class="empty-state">
                <span class="empty-icon">
                    <lord-icon
                        src="https://cdn.lordicon.com/lzsupfwm.json"
                        trigger="hover"
                        style="width:250px;height:250px">
                    </lord-icon>
                </span>
                <p>No hay libros agregados.</p>
                <a href="registrar.php" class="btn btn-primary">Registrar libro</a>
            </div>
        <?php endif; ?>

    </main>

    <footer class="footer">
        <p>&copy; 2026 BiblioTECH · Sistema de Gestión Bibliotecaria</p>
    </footer>

</body>

</html>