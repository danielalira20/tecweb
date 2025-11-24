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

$app->get('/hola/{nombre}', function (Request $request, Response $response, $args) {
    $nombre = $args['nombre'];
    $response->getBody()->write("Hola, $nombre");
    return $response;
});

$app->post('/pruebapost', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $mensaje = $data['mensaje'] ?? 'Sin mensaje recibido';
    $response->getBody()->write("Mensaje recibido: $mensaje");
    return $response;
});

$app->get('/testison', function (Request $request, Response $response) {
    $data = [
        "ok" => true,
        "equipo" => ["Daniela", "Integrante2"]
    ];
    $payload = json_encode($data);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});


$app->addErrorMiddleware(true, true, true);
$app->run();


?>