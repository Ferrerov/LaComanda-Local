<?php

require_once '../entidades/Usuario.php';
require_once '../utilidades/GestorCsv.php';

class LoginControlador
{
    public function Logear($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $usuario = Usuario::TraerUnUsuarioPorNombreUsuario($parametros['usuario']);
        $datosUsuarioJWT = array('id' => $usuario->idUsuario, 'nombreUsuario' => $usuario->nombreUsuario, 'contraseña' =>  $usuario->contraseña, 'tipo' => $usuario->tipo);
        $arrayLog = array($usuario->idUsuario, $usuario->nombreUsuario, $usuario->tipo, (new DateTime('now'))->format('d/m/Y H:i:s'));
        GestorCsv::GuardarCsv($arrayLog, '../archivos/ingresos.log');
        $payload = json_encode(
            array('mensaje' => 'Token de login creado exitosamente', 'token' => AutentificadorJWT::CrearToken($datosUsuarioJWT)));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
/*
    public function LeerLog($request, $response, $args)
    {
        $log = GestorCsv::LeerCsv('../archivos/ingresos.log');
        $payload = json_encode($log);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    */
}

?>