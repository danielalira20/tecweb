<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Read\Read;

header('Content-Type: application/json');

$read = new Read();
$read->list();            // Cargas los datos
echo $read->getData();    // Aquí sí envías el JSON real
