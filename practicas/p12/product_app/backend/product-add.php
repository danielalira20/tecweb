<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Create\Create;

header('Content-Type: application/json');


$producto = json_decode(file_get_contents('php://input'));

if (!empty($producto)) {
    $create = new Create();
    $create->add($producto);  // ejecuta add()

    // getData() devuelve el JSON con el resultado
    echo $create->getData();
    exit;
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos inválidos'
    ]);
    exit;
}
