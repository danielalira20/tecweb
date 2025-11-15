<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Delete\Delete;

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

$delete = new Delete();
$delete->delete($id);
echo $delete->getData();