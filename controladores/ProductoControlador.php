<?php

require_once '../entidades/Producto.php';
require_once '../interfaces/IApiUsable.php';

class ProductoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        $producto = GestorConsultas::TraerUnProducto($idProducto);
        if($producto != null)
        {
            $payload = json_encode($producto);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontro el producto.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $producto = Producto::ConstruirProducto($parametros['idSector'], $parametros['nombreProducto'], $parametros['precio']);

        $idProducto = GestorConsultas::CargarUnProducto($producto);
        $producto->idProducto = $idProducto;
        if($idProducto > 0)
        { 
           $payload = array_merge(array('mensaje' => 'Producto cargado correctamente'),(array)$producto);
           $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar el producto.'));
        }   
        $response->getBody()->write($payload);
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
        $productos = GestorConsultas::TraerTodosLosProductos();
        if($productos != null)
        {
            $payload = json_encode($productos);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron productos.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>