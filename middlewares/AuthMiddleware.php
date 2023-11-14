<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once '../db/AccesoDatos.php';

class AuthMiddleware
{
    public function VerificarAdmin(Request $request, RequestHandler $handler): Response
    {   
        if($request->getMethod() === 'GET')
        {
            $response = $handler->handle($request);
        }
        else
        {
            $parametros = $request->getParsedBody();      
            $nombreUsuarioAdmin = $parametros['nombreUsuarioAdmin'];
            $contraseñaUsuarioAdmin = $parametros['contraseñaUsuarioAdmin'];
            if(AuthMiddleware::BuscarUsuario($nombreUsuarioAdmin, $contraseñaUsuarioAdmin, 'SOCIO') != false)
            {
                $response = $handler->handle($request);
            }
            else
            {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'El usuario no es administrador o esta inactivo'));
                $response->getBody()->write($payload);
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarEmpleado(Request $request, RequestHandler $handler): Response
    {   
        if($request->getMethod() === 'GET')
        {
            $response = $handler->handle($request);
        }
        else
        {
            $parametros = $request->getParsedBody();      
            $nombreUsuarioEmpleado = $parametros['nombreUsuarioEmpleado'];
            $contraseñaUsuarioEmpleado = $parametros['contraseñaUsuarioEmpleado'];
            if(AuthMiddleware::BuscarUsuario($nombreUsuarioEmpleado, $contraseñaUsuarioEmpleado, 'EMPLEADO') != false)
            {
                $response = $handler->handle($request);
            }
            else
            {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'El usuario no es empleado o esta inactivo'));
                $response->getBody()->write($payload);
            }
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function BuscarUsuario($nombreUsuario, $contraseña, $tipo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario' and contraseña = '$contraseña' and tipo = '$tipo' and estado != 'INACTIVO'");
        $consulta->execute();
        return $consulta->fetchObject();
    }
}

?>