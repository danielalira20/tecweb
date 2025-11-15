<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Read\Read;

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

$read = new Read();
$read->single($id);
echo $read->getData();