<?php

include 'includes/funciones.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

iniciarSesionApp();
inicializarLibros();

$errors = $_SESSION['formErrors'] ?? [];
$formData = $_SESSION['formData'] ?? [];

unset($_SESSION['formErrors'], $_SESSION['formData']);

$genres = getGenres();

$val = function (string $slot, string $default = '') use ($formData): string {
    return isset($formData[$slot]) ? sanitizar($formData[$slot]) : $default;
};

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
            <h2>Agregar nuevo libro</h2>
        </div>

        <div class="action-bar">
            <a href="javascript:history.back()" class="btn btn-secondary">Regresar</a>
            <a href="index.php" class="btn btn-secondary">Ver catalogo</a>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <div>
                    <strong>Corrija los siguientes errores:</strong>
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= sanitizar($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="procesar_registro.php" novalidate>

                <div class="form-grid">

                    <!-- ISBN -->
                    <div class="form-group">
                        <label for="isbn">ISBN <span style="color:var(--rojo)">*</span></label>
                        <input type="text" id="isbn" name="isbn" maxlength="<?= MAX_ISBN ?>" placeholder="Ej: 9780451524935" value="<?= $val('isbn') ?>"
                            <?= isset($errors['isbn']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['isbn'] ?? '' ?></span>
                    </div>

                    <!-- Título -->
                    <div class="form-group">
                        <label for="title">Título <span style="color:var(--rojo)">*</span></label>
                        <input type="text" id="title" name="title" placeholder="..." value="<?= $val('title') ?>"
                            <?= isset($errors['title']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['title'] ?? '' ?></span>
                    </div>

                    <!-- Autor -->
                    <div class="form-group">
                        <label for="autor">Autor <span style="color:var(--rojo)">*</span></label>
                        <input type="text" id="autor" name="autor" placeholder="Mín. <?= MIN_AUTOR ?> caracteres" value="<?= $val('autor') ?>"
                            <?= isset($errors['autor']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['autor'] ?? '' ?></span>
                    </div>

                    <!-- Género -->
                    <div class="form-group">
                        <label for="genre">Género <span style="color:var(--rojo)">*</span></label>
                        <select id="genre" name="genre"
                            <?= isset($errors['genre']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                            <option value="">-- Seleccione un género --</option>
                            <?php foreach ($genres as $g): ?>
                                <option value="<?= sanitizar($g) ?>"
                                    <?= ($val('genre') === $g) ? 'selected' : '' ?>>
                                    <?= sanitizar($g) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-msg"><?= $errors['genre'] ?? '' ?></span>
                    </div>

                    <!-- Año de publicación -->
                    <div class="form-group">
                        <label for="year">Año de publicación <span style="color:var(--rojo)">*</span></label>
                        <input type="number" id="year" name="year" min="<?= MIN_YEAR ?>" max="<?= MAX_YEAR ?>" placeholder="<?= MIN_YEAR ?>-<?= MAX_YEAR ?>" value="<?= $val('year') ?>"
                            <?= isset($errors['year']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['year'] ?? '' ?></span>
                    </div>

                    <!-- Páginas -->
                    <div class="form-group">
                        <label for="pages">Número de páginas <span style="color:var(--rojo)">*</span></label>
                        <input type="number" id="pages" name="pages" min="<?= MIN_PAGES ?>" max="<?= MAX_PAGES ?>" placeholder="<?= MIN_PAGES ?>-<?= MAX_PAGES ?>"
                            value="<?= $val('pages') ?>"
                            <?= isset($errors['pages']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['pages'] ?? '' ?></span>
                    </div>

                    <!-- Cantidad en inventario -->
                    <div class="form-group">
                        <label for="stock">Stock en inventario <span style="color:var(--rojo)">*</span></label>
                        <input type="number" id="stock" name="stock" min="<?= MIN_AMOUNT ?>" placeholder="Mín. <?= MIN_AMOUNT ?>" value="<?= $val('stock') ?>"
                            <?= isset($errors['stock']) ? 'style="border-color:var(--rojo)"' : '' ?>>
                        <span class="error-msg"><?= $errors['stock'] ?? '' ?></span>
                    </div>

                    <!-- Disponible -->
                    <div class="form-group" style="justify-content:flex-end;padding-bottom:.4rem;">
                        <div class="checkbox-group">
                            <input type="checkbox" id="available" name="available" value="1"
                                <?= isset($formData['available']) ? 'checked' : '' ?>>
                            <label for="available">Disponible para prestar?</label>
                        </div>
                    </div>

                </div>

                <hr class="section-separator">

                <div>
                    <button type="submit" class="btn btn-primary">
                        <lord-icon
                            src="https://cdn.lordicon.com/pxixoqxa.json"
                            trigger="hover"
                            style="width:30px;height:30px">
                        </lord-icon>
                        Registrar libro
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <lord-icon
                            src="https://cdn.lordicon.com/hfacemai.json"
                            trigger="hover"
                            style="width:30px;height:30px">
                        </lord-icon>
                        Cancelar</a>
                    <span class="form-note">
                        Todos los campos marcados con <span style="color:var(--rojo)">*</span> son obligatorios.
                    </span>
                </div>

            </form>
        </div>

    </main>

</body>

</html>
