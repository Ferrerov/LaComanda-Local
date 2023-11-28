<?php

require_once '../entidades/Pedido.php';
require_once '../interfaces/IApiUsable.php';

class PedidoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idPedido = $args['idPedido'];
        $pedido = GestorConsultas::TraerUnPedido($idPedido);
        if($pedido != null)
        {
            $payload = json_encode($pedido);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontro el pedido.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $producto = GestorConsultas::TraerUnProducto($parametros['idProducto']);
        $pedido = Pedido::ConstruirPedido($parametros['idMesa'], $parametros['idProducto'], $parametros['estado'], $producto->precio);

        $idPedido = GestorConsultas::CargarUnPedido($pedido);
        $pedido->idPedido = $idPedido;
        if($idPedido > 0)
        {
            $payload = json_encode($pedido);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar el pedido.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $tipoModificacion = $request->getAttribute('tipoModificacion');
        $idUsuario = $request->getAttribute('idUsuario');
        $pedidoActualizado = 0;

        //$usuario = GestorConsultas::TraerUnUsuario($idUsuario);
        $pedido = GestorConsultas::TraerUnPedidoPorIdPedidoIdUsuario($parametros['idPedido'], $idUsuario);
        if($pedido != null)
        {
            switch ($tipoModificacion)
            {
                case 'cambioEstado':
                    $pedido->estado = $parametros['estado'];
                    break;
                case 'cambioEstadoTiempo':
                    $pedido->estado = $parametros['estado'];
                    $pedido->tiempoEstimado = $parametros['tiempoEstimado'];
                    break;
                case 'cambioDatos':
                    $pedido->idMesa = $parametros['idMesa'];
                    $pedido->idProducto = $parametros['idProducto'];
                    $pedido->estado = $parametros['estado'];
                    $pedido->precioVenta = $parametros['precioVenta'];
                    break;
            }
            $pedidoActualizado = GestorConsultas::ActualizarUnPedido($pedido);
        }

        if($pedidoActualizado > 0)
        {
            $payload = array_merge(array('mensaje' => 'Pedido actualizado correctamente'),(array)$pedido);
            $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo actualizar el pedido.'));
        }   
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args)
    {

    }
    public function DescargarListado($request, $response, $args)
    {
        $pedidos = GestorConsultas::TraerTodosLosPedidos();

        if (!empty($pedidos)) {
            $csvFileName = 'listado_pedidos.csv';
            $csvFile = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'r+');

            $datosCsv = GestorCsv::GuardarCsv($pedidos, $csvFile);

            $response = $response->withHeader('Content-Type', 'text/csv');
            $response = $response->withHeader('Content-Disposition', 'attachment; filename=' . $csvFileName);
            $response->getBody()->write($datosCsv);

            return $response;
        } else {
            $payload = json_encode(array('mensaje' => 'No hay pedidos para descargar.'));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function CargarListado($request, $response, $args)
    {
        $archivoCsv = $request->getUploadedFiles()['archivoCsv'];

        $arrayDatos = GestorCsv::LeerCsv($archivoCsv->getStream()->getMetadata("uri"));
        if(!empty($arrayDatos))
        {
            GestorConsultas::BorrarTodosLosPedidos();
            foreach ($arrayDatos as $unObjeto)
            {
                $pedido = Pedido::ConstruirPedido($unObjeto[1],$unObjeto[2],$unObjeto[3],$unObjeto[4],$unObjeto[5],$unObjeto[7]);

                $pedido->tiempoInicio = $unObjeto[6];
                
                GestorConsultas::CargarUnPedido($pedido);
            }
            $payload = json_encode(array("mensaje" => "Se cargaron los datos en la tabla pedidos."));
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar.'));
        }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
        $idUsuario = $request->getAttribute('idUsuario');
        if((GestorConsultas::TraerUnUsuario($idUsuario)->tipo == 'SOCIO'))
        {
            $pedidos = GestorConsultas::TraerTodosLosPedidos();
        }
        else
        {
            $pedidos = GestorConsultas::TraerTodosLosPedidosPorIdUsuario($idUsuario);
        }
        if($pedidos != null)
        {
            $payload = json_encode($pedidos);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron pedidos.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}

?>