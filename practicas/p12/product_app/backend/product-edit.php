<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Update\Update;

header('Content-Type: application/json');

$producto = json_decode(file_get_contents('php://input'), true);

if (!empty($producto)) {
    $update = new Update();
    $resultado = $update->edit($producto);

    echo json_encode($resultado);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos inválidos'
    ]);
}
