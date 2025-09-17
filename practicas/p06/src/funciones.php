<?php
function es_multiplo7_5($num)
{
    if ($num%5==0 && $num%7==0)
    {
        echo '<h3>R= El número '.$num.' SÍ es múltiplo de 5 y 7.</h3>';
    }
    else
    {
        echo '<h3>R= El número '.$num.' NO es múltiplo de 5 y 7.</h3>';
    }
}

function secuencia_matriz() {
    $secuencia = [];
    $i = 0;
    
    function esPar($numero) {
        return $numero % 2 == 0;
    }
    
    while (true) {
        $i++;
        $n1 = rand(1, 999); 
        $n2 = rand(1, 999);
        $n3 = rand(1, 999);
        
       
        $secuencia[] = [$n1, $n2, $n3];

        
        if (!esPar($n1) && esPar($n2) && !esPar($n3)) {
            $total = $i * 3;

            echo "<h3>Matriz generada:</h3>";
            echo "<table border='1' cellpadding='5'>";
            foreach ($secuencia as $fila) {
                echo "<tr>";
                foreach ($fila as $num) {
                    echo "<td>$num</td>";
                }
                echo "</tr>";
            }
            echo "</table>";

            echo "<h3>R= " . $total . " números obtenidos en " . $i . " iteraciones.</h3>";
            break; 
        }
    }
}


function primer_numero($numero)
{
    while(true)
    {
        $n1 = rand(1, 999);
        if($n1 % $numero == 0)
        {
            echo '<h4>R= El primer número entero obtenido aleatoriamente que es múltiplo de '.$numero.' es: '.$n1.'</h4>';
            break;
        }
    }
}


function primer_numero_do_while($numero)
{
    do
    {
        $n1 = rand(1, 999);
        if($n1 % $numero == 0)
        {
            echo '<h4>R= El primer número entero obtenido aleatoriamente que es múltiplo de '.$numero.' es: '.$n1.'</h4>';
            break;
        }
    } while(true);
}

function abecedario()
{
    $letras = [];
    for ($i = 97; $i <= 122; $i++) {
        $letras[$i] = chr($i);
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Índice</th><th>Valor</th></tr>";

    foreach ($letras as $key => $value) {
        echo "<tr>";
        echo "<td>$key</td><td>$value</td>";
        echo "</tr>";
    }

    echo "</table>";
}





?>

