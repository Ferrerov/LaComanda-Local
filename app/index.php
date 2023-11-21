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
    $group->post('[/]', \UsuarioControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosUsuario');
    $group->delete('[/]', \UsuarioControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarAdmin');
$app->group('/login', function (RouteCollectorProxy $group) {
  $group->get('[/]', \LoginControlador::class . ':LeerLog')->add(\AuthMiddleware::class . ':VerificarAdmin');;
  $group->post('[/]', \LoginControlador::class . ':Logear')->add(\ValidadorMiddleware::class . ':ValidarCredencialesUsuario');
  });
$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->get('/{idEmpleado}', \EmpleadoControlador::class . ':TraerUno');
    $group->get('[/]', \EmpleadoControlador::class . ':TraerTodos');
    $group->post('[/]', \EmpleadoControlador::class . ':CargarUno')->add(\ValidadorMiddleware::class . ':ValidarDatosEmpleado');
    $group->delete('[/]', \EmpleadoControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarAdmin');
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('/{idProducto}', \ProductoControlador::class . ':TraerUno');
    $group->get('[/]', \ProductoControlador::class . ':TraerTodos');
    $group->post('[/]', \ProductoControlador::class . ':CargarUno');
    $group->delete('[/]', \ProductoControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarEmpleado');
$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('/{idMesa}', \MesaControlador::class . ':TraerUno');
    $group->get('[/]', \MesaControlador::class . ':TraerTodos');
    $group->post('[/]', \MesaControlador::class . ':CargarUno');
    $group->delete('[/]', \MesaControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarEmpleado');
$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('/{idPedido}', \PedidoControlador::class . ':TraerUno');
    $group->get('[/]', \PedidoControlador::class . ':TraerTodos');
    $group->post('[/]', \PedidoControlador::class . ':CargarUno');
    $group->delete('[/]', \PedidoControlador::class . ':BorrarUno');
    })->add(\AuthMiddleware::class . ':VerificarEmpleado');


    //testJWT
    /*$app->group('/jwt', function (RouteCollectorProxy $group) {

        $group->post('/crearToken', function (Request $request, Response $response) {    
          $parametros = $request->getParsedBody();
      
          $usuario = $parametros['usuario'];
          $perfil = $parametros['perfil'];
          $alias = $parametros['alias'];
      
          $datos = array('usuario' => $usuario, 'perfil' => $perfil, 'alias' => $alias);
      
          $token = AutentificadorJWT::CrearToken($datos);
          $payload = json_encode(array('jwt' => $token));
      
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        });
      
        $group->get('/devolverPayLoad', function (Request $request, Response $response) {
          $header = $request->getHeaderLine('Authorization');
          $token = trim(explode("Bearer", $header)[1]);
      
          try {
            $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
          } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
          }
      
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        });
      
        $group->get('/devolverDatos', function (Request $request, Response $response) {
          $header = $request->getHeaderLine('Authorization');
          $token = trim(explode("Bearer", $header)[1]);
      
          try {
            $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
          } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
          }
      
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        });
      
        $group->get('/verificarToken', function (Request $request, Response $response) {
          $header = $request->getHeaderLine('Authorization');
          $token = trim(explode("Bearer", $header)[1]);
          $esValido = false;
      
          try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
          } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
          }
      
          if ($esValido) {
            $payload = json_encode(array('valid' => $esValido));
          }
      
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json');
        });
      });*/

$app->run();