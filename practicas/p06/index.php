<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Práctica 4</title>
</head>

<body>
    <h2>Ejercicio 1</h2>
    <p>Escribir programa para comprobar si un número es un múltiplo de 5 y 7</p>
    <?php
    require_once __DIR__ . '/src/funciones.php';

        if(isset($_GET['numero']))
        {
        es_multiplo7_5($_GET['numero']);
        }
    ?>

    <h2>Ejercicio 2</h2>
    <p>
    Crea un programa para la generación repetitiva de 3 número aleatorios hasta obtener una secuencia compuesta por:impar, par, impar.
    <br>
    Estos números deben almacenarse en una matriz de Mx3, donde M es el número de filas y 3 el número de columnas. </p>

    <?php
        require_once __DIR__ . '/src/funciones.php';
        secuencia_matriz();
    ?>

        
</body>
</html>