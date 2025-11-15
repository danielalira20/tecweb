<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Read\Read;

header('Content-Type: application/json');

// Verificar si llega el ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID no recibido'
    ]);
    exit;
}

$id = intval($_POST['id']);

// Obtener producto
$read = new Read();
$resultado = $read->single($id);

// Validar resultado
if (!$resultado || !isset($resultado['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Producto no encontrado'
    ]);
    exit;
}

echo json_encode($resultado);
