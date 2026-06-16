<?php

session_start();
session_unset();
session_destroy();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=index.php">
    <title>Cerrando sesión…</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body>
    <div class="goodbye">
        <span class="big-icon">
            <lord-icon
                src="https://cdn.lordicon.com/txuhvtae.json"
                trigger="hover"
                colors="primary:#121331,secondary:#e8b730,tertiary:#ebe6ef,quaternary:#3a3347,quinary:#f24c00,senary:#f9c9c0,septenary:#b26836"
                style="width:30px;height:30px">
            </lord-icon>
        </span>
        <h2>Sesión cerrada</h2>
        <p>Gracias por usar BiblioTech.<br>Redirigiendo al inicio en 3 segundos…</p>
        <a href="index.php" class="btn btn-primary">
            <lord-icon
                src="https://cdn.lordicon.com/zbtbhzsg.json"
                trigger="morph"
                state="morph-open"
                style="width:30px;height:30px">
            </lord-icon>
            Volver al inicio</a>
    </div>
</body>

</html>