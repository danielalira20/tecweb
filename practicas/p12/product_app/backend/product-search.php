<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Read\Read;

header('Content-Type: application/json');

$search = $_GET['search'] ?? '';

$read = new Read();
$read->search($search);
echo $read->getData();