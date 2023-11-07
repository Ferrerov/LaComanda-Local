<?php

require_once '../entidades/Mesa.php';
require_once '../interfaces/IApiUsable.php';

class MesaControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idMesa = $args['idMesa'];
        $mesa = Mesa::TraerUnaMesa($idMesa);
        if($mesa != null)
        {
            $response->getBody()->write(json_encode($mesa));
        }
        else
        {
            $response->getBody()->write("No se encontro la mesa.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesa = Mesa::ConstruirMesa($parametros['idTrabajador'], $parametros['nombreCliente'],$parametros['estado']);

        echo("Cargando una mesa...</br>");
        if($mesa->CargarUnaMesa() > 0)
        {
            $response->getBody()->write("Se cargo la mesa:</br>");
            $response->getBody()->write($mesa->MostrarDatos());
        }
        else
        {
            $response->getBody()->write("No se pudo cargar la mesa.</br>");
        }
     
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {

    }
    public function BorrarUno($request, $response, $args)
    {

    }
    public function TraerTodos($request, $response, $args)
    {
        $mesas = Mesa::TraerTodasLasMesas();
        if($mesas != null)
        {
            $response->getBody()->write(json_encode($mesas));
        }
        else
        {
            $response->getBody()->write("No se encontro ninguna mesa.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>