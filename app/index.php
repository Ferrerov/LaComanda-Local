<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once '../controladores/UsuarioControlador.php';
require_once '../controladores/ProductoControlador.php';
require_once '../controladores/MesaControlador.php';
require_once '../controladores/PedidoControlador.php';

// Instantiate App
$app = AppFactory::create();
$app->setBasePath('/slim-LaComanda/app');

$app->addRoutingMiddleware();
// Add parse body
$app->addBodyParsingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('/{idUsuario}', \UsuarioControlador::class . ':TraerUno');
    $group->get('[/]', \UsuarioControlador::class . ':TraerTodos');
    $group->post('[/]', \UsuarioControlador::class . ':CargarUno');
    $group->delete('[/]', \UsuarioControlador::class . ':BorrarUno');
    });
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/{idProducto}', \ProductoControlador::class . ':TraerUno');
    $group->get('[/]', \ProductoControlador::class . ':TraerTodos');
    $group->post('[/]', \ProductoControlador::class . ':CargarUno');
    $group->delete('[/]', \ProductoControlador::class . ':BorrarUno');
    });
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('/{idMesa}', \MesaControlador::class . ':TraerUno');
    $group->get('[/]', \MesaControlador::class . ':TraerTodos');
    $group->post('[/]', \MesaControlador::class . ':CargarUno');
    $group->delete('[/]', \MesaControlador::class . ':BorrarUno');
    });
$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/{idPedido}', \PedidoControlador::class . ':TraerUno');
    $group->get('[/]', \PedidoControlador::class . ':TraerTodos');
    $group->post('[/]', \PedidoControlador::class . ':CargarUno');
    $group->delete('[/]', \PedidoControlador::class . ':BorrarUno');
    });

$app->run();