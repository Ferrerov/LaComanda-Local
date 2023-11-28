<?php

require_once '../entidades/Empleado.php';
require_once '../interfaces/IApiUsable.php';

class EmpleadoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idEmpleado = $args['idEmpleado'];
        $empleado = GestorConsultas::TraerUnEmpleado($idEmpleado);
        if($empleado != null)
        {
            $response->getBody()->write(json_encode($empleado));
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo encontrar el empleado.'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $empleado = Empleado::ConstruirEmpleado($parametros['idUsuario'], $parametros['idSector'], $parametros['tipo'], $parametros['estado']);

        $idEmpleado = GestorConsultas::CargarUnEmpleado($empleado);
        $empleado->idEmpleado = $idEmpleado;
        if($idEmpleado > 0)
        {
            $payload = array_merge(array('mensaje' => 'Empleado cargado correctamente'),(array)$empleado);
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
        if(GestorConsultas::BorrarUnEmpleado($parametros['idEmpleado']) > 0)
        {
            $payload = json_encode(array('mensaje' => "Se borro el empleado de id: {$parametros['idEmpleado']}"));
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo borrar el empleado.'));  
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
        $empleados = GestorConsultas::TraerTodosLosEmpleados();
        if($empleados != null)
        {
            $payload = json_encode($empleados); 
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron empleados.'));  
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>