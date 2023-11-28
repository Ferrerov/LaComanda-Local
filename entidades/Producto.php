<?php

class Producto{
    public $idProducto;
    public $idSector;
    public $nombreProducto;
    public $precio;

    public function __construct() {}

    public static function ConstruirProducto($idSector, $nombreProducto, $precio)
    {
        $producto = new Producto();

        $producto->idSector = $idSector;
        $producto->nombreProducto = strtoupper($nombreProducto);
        $producto->precio = $precio;

        return $producto;
    }

}

?>