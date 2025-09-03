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
    ?>

    <p>En el segundo bloque, se reasignó el valor de <code>$a</code> a "PHP server". 
    Como <code>$b</code> y <code>$c</code> son referencias a <code>$a</code>, 
    todos muestran el mismo valor. Esto demuestra cómo las referencias en PHP 
    mantienen sincronizados los valores entre variables.</p>

</body>
</html>