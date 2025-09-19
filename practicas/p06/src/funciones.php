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

$vehiculos = [
    "AXQ9021" => [
        "Auto" => ["marca" => "TOYOTA", "modelo" => 2021, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Valeria Montoya", "ciudad" => "Durango", "direccion" => "Blvd. Guadiana 345"]
    ],
    "LMP7642" => [
        "Auto" => ["marca" => "HONDA", "modelo" => 2019, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Jorge Estrada", "ciudad" => "Aguascalientes", "direccion" => "Col. Las Américas 118"]
    ],
    "CRD5128" => [
        "Auto" => ["marca" => "SEAT", "modelo" => 2018, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Elisa Contreras", "ciudad" => "Saltillo", "direccion" => "Av. Universidad 900"]
    ],
    "ZNB8473" => [
        "Auto" => ["marca" => "JEEP", "modelo" => 2017, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Raúl Hernández", "ciudad" => "Hermosillo", "direccion" => "Blvd. Kino 2301"]
    ],
    "QTR2389" => [
        "Auto" => ["marca" => "TESLA", "modelo" => 2022, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Camila Vargas", "ciudad" => "La Paz", "direccion" => "Malecón Costero 500"]
    ],
    "YHK6590" => [
        "Auto" => ["marca" => "NISSAN", "modelo" => 2016, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Mario Rivera", "ciudad" => "Colima", "direccion" => "Av. Felipe Sevilla 212"]
    ],
    "BRF7821" => [
        "Auto" => ["marca" => "MAZDA", "modelo" => 2020, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Rosa Morales", "ciudad" => "Tuxtla Gutiérrez", "direccion" => "5a Norte 432"]
    ],
    "KDW9345" => [
        "Auto" => ["marca" => "FORD", "modelo" => 2019, "tipo" => "camioneta"],
        "Propietario" => ["nombre" => "Diego López", "ciudad" => "Ensenada", "direccion" => "Zona Centro 145"]
    ],
    "PGX3142" => [
        "Auto" => ["marca" => "VOLKSWAGEN", "modelo" => 2018, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Andrea Ramírez", "ciudad" => "Villahermosa", "direccion" => "Col. Tabasco 2000, 67"]
    ],
    "VRC5604" => [
        "Auto" => ["marca" => "BMW", "modelo" => 2021, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Hugo Paredes", "ciudad" => "Mazatlán", "direccion" => "Av. Del Mar 888"]
    ],
    "XNJ2458" => [
        "Auto" => ["marca" => "AUDI", "modelo" => 2023, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Isabella Romero", "ciudad" => "Culiacán", "direccion" => "Forjadores 678"]
    ],
    "MZS7810" => [
        "Auto" => ["marca" => "MERCEDES-BENZ", "modelo" => 2019, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Fernando Silva", "ciudad" => "Tepic", "direccion" => "Av. Insurgentes 451"]
    ],
    "DLF4372" => [
        "Auto" => ["marca" => "FIAT", "modelo" => 2015, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Samantha Ortega", "ciudad" => "Irapuato", "direccion" => "Blvd. Díaz Ordaz 75"]
    ],
    "HQR9025" => [
        "Auto" => ["marca" => "RENAULT", "modelo" => 2017, "tipo" => "sedan"],
        "Propietario" => ["nombre" => "Ángel Domínguez", "ciudad" => "Los Mochis", "direccion" => "Av. Independencia 300"]
    ],
    "TPW6874" => [
        "Auto" => ["marca" => "PEUGEOT", "modelo" => 2020, "tipo" => "hatchback"],
        "Propietario" => ["nombre" => "Natalia García", "ciudad" => "San Cristóbal de las Casas", "direccion" => "Real de Guadalupe 120"]
    ]
];

function buscarPorMatricula($matricula) {
    global $vehiculos;
    return $vehiculos[$matricula] ?? false;
}

function obtenerTodosLosAutos() {
    global $vehiculos;
    return $vehiculos;
}






?>

