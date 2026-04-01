<?php

require __DIR__ . '/../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


$dispatcher = simpleDispatcher(function(RouteCollector $r) {
    $rotas = require __DIR__ . '/../src/Routes/routes.php';
    $rotas($r);
});

// 3. Detectar método e URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// 4. Remover querystring
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

// 5. Ajustar prefixo do projeto (XAMPP)
$uri = str_replace('/album_api/public', '', $uri);

// 6. Despachar rota
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {

    case FastRoute\Dispatcher::NOT_FOUND:
        echo json_encode(['error' => 'Rota não encontrada']);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo json_encode(['error' => 'Método não permitido']);
        break;

    case FastRoute\Dispatcher::FOUND:
        [$classe, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        (new $classe())->$method($vars);
        break;
}
