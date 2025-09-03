<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 3</title>
</head>
<body>
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        //AQUI VA MI CÓDIGO PHP
        $_myvar;
        $_7var;
        //myvar;       // Inválida
        $myvar;
        $var7;
        $_element1;
        //$house*5;     // Invalida
        
        echo '<h4>Respuesta:</h4>';   
    
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
        echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
        echo '</ul>';

        unset($_myvar, $_7var, $myvar, $var7, $_element1);
    ?>

    <h2>Ejercicio 2</h2>
    <p>Proporcionar los valores de $a, $b, $c como sigue:</p>
    <?php 
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;
    ?>
    <p> Ahora muestra el contenido de cada variable </p>
    <?php 
        echo "<p>El valor de \$a es: $a</p>";
        echo "<p>El valor de \$b es: $b</p>";
        echo "<p>El valor de \$c es: $c</p>";
    ?>
    <p>Agrega al código actual las siguientes asignaciones: </p>
    <?php    
        $a = "PHP server";
        $b = &$a;
        echo "<p>El valor de \$a es: $a</p>";
        echo "<p>El valor de \$b es: $b</p>";
        echo "<p>El valor de \$c es: $c</p>";

         unset($a, $b, $c);
    ?>

    <p>En el segundo bloque, se reasignó el valor de <code>$a</code> a "PHP server". 
    Como <code>$b</code> y <code>$c</code> son referencias a <code>$a</code>, 
    todos muestran el mismo valor. Esto demuestra cómo las referencias en PHP 
    mantienen sincronizados los valores entre variables.</p>

    <h2>Ejercicio 3</h2>
    <p>Muestra el contenido de cada variable inmediatamente después de cada asignación:</p>
    <?php
        $a = "PHP5 ";
        echo "<p>1. \$a = $a</p>";

        $z[] = &$a;
        echo "<p>2. \$z[0] (referencia a \$a) = {$z[0]}</p>";

        $b = "5a version de PHP";
        echo "<p>3. \$b = $b</p>";

        $c = (int)$b * 10;
        echo "<p>4. \$c = $c</p>";

        $a .= $b;
        echo "<p>5. \$a después de concatenar con \$b = $a</p>";

        $b = (int)$b * $c;
        echo "<p>6. \$b después de multiplicar con \$c = $b</p>";

        $z[0] = "MySQL";
        echo "<p>7. \$z[0] modificado = {$z[0]}</p>";

        echo "<p>Contenido completo de \$z:</p>";
        foreach ($z as $key => $value) {
            echo "Índice $key: $value<br>";
        }
        ?>

        <h2>Ejercicio 4</h2>
        <p>Lee y muestra los valores de las variables del ejercicio anterior, pero ahora con la ayuda de la matriz $GLOBALS o del modificador global de PHP.</p>

        <?php
            echo "<p>\$a: " . $GLOBALS['a'] . "</p>";
            echo "<p>\$b: " . $GLOBALS['b'] . "</p>";
            echo "<p>\$c: " . $GLOBALS['c'] . "</p>";

            echo "<p>\$z:</p>";
            foreach ($GLOBALS['z'] as $indice => $valor) {
                echo "<p>Índice $indice: $valor</p>";
            }
        ?>

        <h2>Ejercicio 5</h2>
    <p>Dar el valor de las variables $a, $b, $c al final del siguiente script:</p>
    <?php
        $a = "7 personas";
        $b = (integer) $a;
        $a = "9E3";
        $c = (double) $a;
        echo "<p>\$a es: $a</p>";
        echo "<p>\$b es: $b</p>";
        echo "<p>\$c es: $c</p>";
        unset($a, $b, $c);  
    ?>

     <h2>Ejercicio 6</h2>
   <p>Dar y comprobar el valor booleano de las variables $a, $b, $c, $d, $e y $f usando var_dump():</p>

<?php
    $a = "0";
    $b = "TRUE";
    $c = FALSE;
    $d = ($a OR $b);
    $e = ($a AND $c);
    $f = ($a XOR $b);

    echo "<pre>";
    echo "\$a = "; var_dump($a);
    echo "\$b = "; var_dump($b);
    echo "\$c = "; var_dump($c);
    echo "\$d = "; var_dump($d);
    echo "\$e = "; var_dump($e);
    echo "\$f = "; var_dump($f);
    echo "</pre>";

    echo "<h4>Transformar el valor booleano de \$c y \$e en uno que se pueda mostrar con echo:</h4>";
    echo "<p>c: " . ($c ? 'true' : 'false') . "</p>";
    echo "<p>e: " . ($e ? 'true' : 'false') . "</p>";

    unset($a, $b, $c, $d, $e, $f);
?>

<h2>Ejercicio 7</h2>
    <p>Usando la variable predefinida $_SERVER, determina lo siguiente:</p>
    <ul>
        <li>a. La versión de Apache y PHP: <?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE']); ?></li>
        <li>b. El nombre del sistema operativo (servidor): <?php echo htmlspecialchars(PHP_OS); ?></li>
        <li>c. El idioma del navegador (cliente): <?php echo htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']); ?></li>
    </ul>
   
</body>
</html>