<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Probando conexión...<br>";

$link = new mysqli('localhost', 'root', 'daniela20', 'marketzone');

if ($link->connect_errno) {
    die('Error de conexión: ' . $link->connect_error);
}

echo "Conexión exitosa!<br>";

$result = $link->query("SELECT * FROM productos LIMIT 1");

if ($result) {
    echo "Consulta exitosa!<br>";
    echo "Productos encontrados: " . $result->num_rows;
} else {
    echo "Error en consulta: " . $link->error;
}

$link->close();
?>