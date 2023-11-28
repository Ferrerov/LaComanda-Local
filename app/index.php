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
require_once '../controladores/LoginControlador.php';
require_once '../controladores/UsuarioControlador.php';
require_once '../controladores/EmpleadoControlador.php';
require_once '../controladores/ProductoControlador.php';
require_once '../controladores/MesaControlador.php';
require_once '../controladores/PedidoControlador.php';
require_once '../middlewares/AuthMiddleware.php';
require_once '../middlewares/ValidadorMiddleware.php';
require_once '../utilidades/AutentificadorJWT.php';


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
    $group->post('[/]', \UsuarioControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosUsuarioCarga');
    $group->delete('[/]', \UsuarioControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarAdmin');
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->get('[/]', \LoginControlador::class . ':LeerLog')->add(\AuthMiddleware::class . ':VerificarAdmin');;
  $group->post('[/]', \LoginControlador::class . ':Logear')->add(\ValidadorMiddleware::class . ':ValidarCredencialesUsuario');
  });
$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->get('/{idEmpleado}', \EmpleadoControlador::class . ':TraerUno');
    $group->get('[/]', \EmpleadoControlador::class . ':TraerTodos');
    $group->post('[/]', \EmpleadoControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosEmpleadoCarga');
    $group->delete('[/]', \EmpleadoControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarAdmin');
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/{idProducto}', \ProductoControlador::class . ':TraerUno');
    $group->get('[/]', \ProductoControlador::class . ':TraerTodos');
    $group->post('[/]', \ProductoControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosProductoCarga');
    $group->delete('[/]', \ProductoControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarEmpleado');
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('/{idMesa}', \MesaControlador::class . ':TraerUno');
    $group->get('[/]', \MesaControlador::class . ':TraerTodos');
    $group->post('[/]', \MesaControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosMesaCarga');
    $group->delete('[/]', \MesaControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarEmpleado');
$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/descargar', \PedidoControlador::class . ':DescargarListado');
    $group->post('/cargar', \PedidoControlador::class . ':CargarListado');
    $group->get('/{idPedido}', \PedidoControlador::class . ':TraerUno')->add(\AuthMiddleware::class . ':VerificarEmpleado');
    $group->get('[/]', \PedidoControlador::class . ':TraerTodos')->add(\AuthMiddleware::class . ':VerificarEmpleado');
    $group->post('[/]', \PedidoControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosPedidoCarga')->add(\AuthMiddleware::class . ':VerificarMozo')->add(\AuthMiddleware::class . ':VerificarEmpleado');
    $group->put('[/]', \PedidoControlador::class . ':ModificarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosPedidoModificacion')->add(\AuthMiddleware::class . ':VerificarEmpleado');
    $group->delete('[/]', \PedidoControlador::class . ':BorrarUno')->add(\AuthMiddleware::class . ':VerificarEmpleado');
    });

$app->run();