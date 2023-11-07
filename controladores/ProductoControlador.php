<?php

require_once '../entidades/Producto.php';
require_once '../interfaces/IApiUsable.php';

class ProductoControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idProducto = $args['idProducto'];
        $producto = Producto::TraerUnProducto($idProducto);
        if($producto != null)
        {
            $response->getBody()->write(json_encode($producto));
        }
        else
        {
            $response->getBody()->write("No se encontro el producto.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $producto = Producto::ConstruirProducto($parametros['idSector'], $parametros['nombre'], $parametros['precio']);

        echo("Cargando un producto...</br>");
        if($producto->CargarUnProducto() > 0)
        {
            $response->getBody()->write("Se cargo el producto:</br>");
            $response->getBody()->write($producto->MostrarDatos());
        }
        else
        {
            $response->getBody()->write("No se pudo cargar el producto.</br>");
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
        $productos = Producto::TraerTodosLosProductos();
        if($productos != null)
        {
            $response->getBody()->write(json_encode($productos));
        }
        else
        {
            $response->getBody()->write("No se encontro ningun producto.</br>");
        }
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>