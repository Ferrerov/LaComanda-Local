<?php

require_once '../entidades/Empleado.php';
require_once '../interfaces/IApiUsable.php';

class EmpleadoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idEmpleado = $args['idEmpleado'];
        $empleado = Usuario::TraerUnUsuario($idEmpleado);
        if($empleado != null)
        {
            $response->getBody()->write(json_encode($empleado));
        }
        else
        {
            $response->getBody()->write("No se encontro el empleado.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $empleado = Empleado::ConstruirEmpleado($parametros['idUsuario'], $parametros['idSector'], $parametros['tipo'], $parametros['nombreEmpleado'], $parametros['estado']);

        if($empleado->CargarUnEmpleado() > 0)
        {
            $payload = json_encode(array('mensaje' => 'Se cargo el empleado.', 'idUsuario' => $parametros['idUsuario'], 'idSector' => $parametros['idSector'], 'tipo' => $parametros['tipo'], 'nombreEmpleado' => $parametros['nombreEmpleado'], 'estado' => $parametros['estado']));
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