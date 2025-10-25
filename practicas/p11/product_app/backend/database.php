<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'daniela20',
        'marketzone'
    );

    /**
     * NOTA: si la conexión falló $conexion contendrá false
     **/
    if(!$conexion) {
        die('¡Base de datos NO conextada!');
    }

    // Establecer charset UTF-8
    mysqli_set_charset($conexion, "utf8");
?>