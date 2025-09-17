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

    <h2>Ejercicio 3</h2>
    <p>Utiliza un ciclo while para encontrar el primer número entero obtenido aleatoriamente,
    pero que además sea múltiplo de un número dado.</p>
    <ul>
        <li>
        Crear una variante de este script utilizando el ciclo do-while.
        </li>
        <li>
        El número dado se debe obtener vía GET.
        </li>
    </ul>
    <h3> Ejercicio Usando while</h3>
    <?php
        require_once __DIR__ . '/src/funciones.php';
        if(isset($_GET['numero']))
        {
        primer_numero($_GET['numero']);
        }
    ?>
    <h3>Ejercicio Usando do-while</h3>
    <?php
        require_once __DIR__ . '/src/funciones.php';
        if(isset($_GET['numero']))
        {
        primer_numero_do_while($_GET['numero']);
        }
    ?>

    <h2>Ejercicio 4</h2>
    <p>Crear un arreglo cuyos índices van de 97 a 122 y cuyos valores son las letras de la ‘a’
    a la "z". Usa la función chr(n) que devuelve el caracter cuyo código ASCII es n para poner
    el valor en cada índice. Es decir:</p>
    <p>[97] => a
        <br>
    [98] => b
    <br>
    [99] => c
    <br>
    ...
    <br>
    [122] => z
    </p>
    <ul>
        <li>Crea el arreglo con un ciclo for</li>
        <li>Lee el arreglo y crea una tabla en XHTML con echo y un ciclo foreach
            foreach ($arreglo as $key => $value) {
            # code...
            } </li>
    </ul>

    <?php
        require_once __DIR__ . '/src/funciones.php';
        abecedario();
    ?>

    <h2>Ejercicio 5</h2>
    <form action="src/xhtml.php" method="post">
        <label for="edad">Edad:</label>
        <input type="number" name="edad" id="edad" required><br><br>

        <label>Sexo:</label>
        <input type="radio" name="sexo" value="femenino" required> Femenino
        <input type="radio" name="sexo" value="masculino" required> Masculino <br><br>

        <input type="submit">
    </form>
    <br>




        
</body>
</html>