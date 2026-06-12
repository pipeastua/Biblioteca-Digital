<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$duracion = 604800;
ini_set('session.gc_maxlifetime', $duracion);
session_set_cookie_params([
    'lifetime' => $duracion,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
include 'includes/funciones.php';

if (!isset($_SESSION['libros'])) {
    $_SESSION['libros'] = stockLibros();
}

$changedMsg = '';

if (isset($_GET['accion'], $_GET['isbn']) && $_GET['accion'] === 'cambiarDispo' && !empty($_GET['isbn'])) {
    
    $isbnPoint = sanitizar($_GET['isbn']);
    $found = false;

    foreach ($_SESSION['libros'] as $key => $libro) {
        if ($libro['isbn'] === $isbnPoint) {
            
            $_SESSION['libros'][$key]['disponible'] = !$_SESSION['libros'][$key]['disponible'];
            $newState = $_SESSION['libros'][$key]['disponible'] ? 'Disponible' : 'No disponible';
            $changedMsg = "Estado del libro <strong>\"" . sanitizar($libro['titulo']) . "\"</strong> cambiado a: <strong> {$newState} </strong>";
            $found = true;
            break;
        }
    }
    unset($libro);

    if (!$found) {
        $changedMsg = "ISBN no encontrado";
    }
}

$libros = $_SESSION['libros'];
$stats  = stats($libros);
$total  = count($libros);
?>

<html>
    <h1> SI FUNKA </h1>
</html>