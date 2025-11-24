<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/tecweb/practicas/p13'); 

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("¡Hola mundo Slim 4!");
    return $response;
});

$app->run();


?>