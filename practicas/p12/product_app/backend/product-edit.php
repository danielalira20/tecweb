<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Update\Update;

header('Content-Type: application/json');

$producto = json_decode(file_get_contents('php://input'));

if (!empty($producto)) {
    $update = new Update();
    $update->edit($producto);
    echo $update->getData();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
}