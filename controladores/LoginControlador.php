<?php

require_once '../entidades/Usuario.php';
require_once '../entidades/Empleado.php';
require_once '../utilidades/GestorCsv.php';

class LoginControlador
{
    public function Logear($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $usuario = GestorConsultas::TraerUnUsuarioPorNombreUsuario($parametros['usuario']);
        if($usuario->tipo == 'SOCIO')
        {
            $datosUsuarioJWT = array('idUsuario' => $usuario->idUsuario, 'nombreUsuario' => $usuario->nombreUsuario, 'contraseña' =>  $usuario->contraseña, 'tipoUsuario' => $usuario->tipo, 'tipoEmpleado' => 'SOCIO');
        }
        else
        {
            $empleado = GestorConsultas::TraerUnEmpleadoPorIdUsuario($usuario->idUsuario);  
            $datosUsuarioJWT = array('idUsuario' => $usuario->idUsuario, 'nombreUsuario' => $usuario->nombreUsuario, 'contraseña' =>  $usuario->contraseña, 'tipoUsuario' => $usuario->tipo, 'tipoEmpleado' => $empleado->tipo);
        }
        $payload = json_encode(
            array('mensaje' => 'Token de login creado exitosamente', 'token' => AutentificadorJWT::CrearToken($datosUsuarioJWT)));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>