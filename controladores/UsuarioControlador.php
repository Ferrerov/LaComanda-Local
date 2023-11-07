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
        $validacion = Usuario::ValidarInput($parametros['nombreUsuario'], $parametros['contraseña'], $parametros['tipo'], $parametros['estado']);
        if($validacion != '')
        {
            $response->getBody()->write($validacion);
        }
        else
        {
            $usuario = Usuario::ConstruirUsuario($parametros['nombreUsuario'], $parametros['contraseña'], $parametros['tipo'], $parametros['estado']);

            echo("Cargando un usuario...</br>");
            if($usuario->CargarUnUsuario() > 0)
            {
                $response->getBody()->write("Se cargo el usuario:</br>");
                $response->getBody()->write($usuario->MostrarDatos());
            }
            else
            {
                $response->getBody()->write("No se pudo cargar el usuario.</br>");
            }
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