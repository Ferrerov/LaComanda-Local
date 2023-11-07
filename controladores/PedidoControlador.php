<?php

require_once '../entidades/Pedido.php';
require_once '../interfaces/IApiUsable.php';

class PedidoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedido = Pedido::TraerUnPedido($idPedido);
        if($pedido != null)
        {
            $response->getBody()->write(json_encode($pedido));
        }
        else
        {
            $response->getBody()->write("No se encontro el pedido.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedido = Pedido::ConstruirPedido($parametros['idMesa'], $parametros['idMozo'], $parametros['idProducto'], $parametros['estado'],$parametros['tiempoEstimado'], $parametros['tiempoInicio'], $parametros['tiempoFin'], $parametros['precioVenta']);

        echo("Cargando un pedido...</br>");
        if($pedido->CargarUnPedido() > 0)
        {
            $response->getBody()->write("Se cargo el pedido:</br>");
            $response->getBody()->write($pedido->MostrarDatos());
        }
        else
        {
            $response->getBody()->write("No se pudo cargar el pedido.</br>");
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
        $pedidos = Pedido::TraerTodosLosPedidos();
        if($pedidos != null)
        {
            $response->getBody()->write(json_encode($pedidos));
        }
        else
        {
            $response->getBody()->write("No se encontro ningun pedido.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>