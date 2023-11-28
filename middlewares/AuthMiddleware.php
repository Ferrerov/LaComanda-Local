<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once '../db/AccesoDatos.php';

class AuthMiddleware
{

    public function VerificarAdmin(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $datos = AuthMiddleware::ObtenerDatosJWT($request);
        if($datos != false)
        {
            if($datos->tipoUsuario == 'SOCIO')
            {
                $response = $handler->handle($request);
            }
            else
            {
                $payload = json_encode(array("error" => "El usuario no es socio."));
                $response->getBody()->write($payload);
            }
        }   
        else
        {
            $payload = json_encode(array("error" => "Se requiere el token JWT para acceder"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function VerificarEmpleado(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $datos = AuthMiddleware::ObtenerDatosJWT($request);
        if($datos != false)
        {
            if($datos->tipoUsuario == 'EMPLEADO' || $datos->tipoUsuario == 'SOCIO')
            {
                $request = $request->withAttribute('idUsuario', $datos->idUsuario);
                $response = $handler->handle($request);
            }
            else
            {
                $payload = json_encode(array("error" => "El usuario no es socio ni empleado."));
                $response->getBody()->write($payload);
            }
        }   
        else
        {
            $payload = json_encode(array("error" => "Se requiere el token JWT para acceder"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function VerificarMozo(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $datos = AuthMiddleware::ObtenerDatosJWT($request);
        if($datos != false)
        {
            if($datos->tipoEmpleado == 'MOZO' || $datos->tipoUsuario == 'SOCIO')
            {
                $response = $handler->handle($request);
            }
            else
            {
                $payload = json_encode(array("error" => "El usuario no es socio ni mozo."));
                $response->getBody()->write($payload);
            }
        }   
        else
        {
            $payload = json_encode(array("error" => "Se requiere el token JWT para acceder"));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    private static function ObtenerDatosJWT($request)
    {
        $header = $request->getHeaderLine('authorization');
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            return AutentificadorJWT::ObtenerData($token);
        }
        return false;
    }
}

?>