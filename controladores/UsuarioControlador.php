<?php

require_once '../entidades/Usuario.php';
require_once '../interfaces/IApiUsable.php';

class UsuarioControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idUsuario = $args['idUsuario'];
        $usuario = Usuario::TraerUnUsuario($idUsuario);
        if($usuario != null)
        {
            $response->getBody()->write(json_encode($usuario));
        }
        else
        {
            $response->getBody()->write("No se encontro el usuario.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::ConstruirUsuario($parametros['nombreUsuario'], $parametros['contraseña'], $parametros['tipo'], $parametros['estado']);

        if($usuario->CargarUnUsuario() > 0)
        {
            $payload = json_encode(array('mensaje' => 'Se cargo el usuario.', 'nombreUsuario' => $parametros['nombreUsuario'], 'contraseña' => $parametros['contraseña'], 'tipo' => $parametros['tipo'], 'estado' => $parametros['estado']));
            $response->getBody()->write($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar el usuario.'));
            $response->getBody()->write($payload);
        }
     
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {

    }
    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        if(Usuario::BorrarUnUsuario($parametros['idUsuario']) > 0)
        {
            $response->getBody()->write("Se borro el usuario de id: {$parametros['idUsuario']}.</br>");
        }
        else
        {
            $response->getBody()->write("No se encontro ningun usuario.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
        $usuarios = Usuario::TraerTodosLosUsuarios();
        if($usuarios != null)
        {
            $response->getBody()->write(json_encode($usuarios));
        }
        else
        {
            $response->getBody()->write("No se encontro ningun usuario.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>