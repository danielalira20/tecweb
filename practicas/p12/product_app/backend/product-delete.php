<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Delete\Delete;

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

$delete = new Delete();
$resultado = $delete->delete($id);

echo json_encode($resultado);
