<?php

require_once '../entidades/Usuario.php';
require_once '../interfaces/IApiUsable.php';

class UsuarioControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idUsuario = $args['idUsuario'];
        $usuario = GestorConsultas::TraerUnUsuario($idUsuario);
        if($usuario != null)
        {
            $payload = json_encode($usuario);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontro el usuario.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ConstruirUsuario($parametros['nombreUsuario'], $parametros['contraseña'], $parametros['nombre'], $parametros['apellido'], $parametros['tipo'], $parametros['estado']);

        $idUsuario = GestorConsultas::CargarUnUsuario($usuario);
        $usuario->idUsuario = $idUsuario;
        if($idUsuario > 0)
        {
            $payload = array_merge(array('mensaje' => 'Usuario cargado correctamente'),(array)$usuario);
            $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar el usuario.'));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {

    }
    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(GestorConsultas::BorrarUnUsuario($parametros['idUsuario']) > 0)
        {
            $payload = json_encode(array('mensaje' => "Se borro el usuario de id: {$parametros['idUsuario']}"));
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar el usuario.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
        $usuarios = GestorConsultas::TraerTodosLosUsuarios();
        if($usuarios != null)
        {
            $payload = json_encode($usuarios);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron usuarios.'));  
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>