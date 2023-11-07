<?php

class Producto{
    public $idProducto;
    public $idSector;
    public $nombre;
    public $precio;

    public function __construct() {}

    public static function ConstruirProducto($idSector, $nombre, $precio)
    {
        $producto = new Producto();

        $producto->idSector = $idSector;
        $producto->nombre = $nombre;
        $producto->precio = $precio;

        return $producto;
    }

    public function MostrarDatos()
    {
        return '----------------------------------------------</br>
            Nombre del producto:' . $this->nombre . '</br>
            Precio: '. $this->precio . '</br>
            Id del sector: ' . $this->idSector . '</br>
            ----------------------------------------------</br>';
    }

    public static function TraerUnProducto($idProducto)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idProducto, idSector, nombre, precio from productos where idProducto = $idProducto");
        $consulta->execute();
        $productoBuscado = $consulta->fetchObject('producto');
        return $productoBuscado;
    }

    public static function TraerTodosLosProductos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from productos");
        $consulta->execute();
        $productosBuscado = $consulta->fetchAll(PDO::FETCH_CLASS,'producto');
        return $productosBuscado;
    }

    public function CargarUnProducto()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into productos (idSector, nombre, precio) values('$this->idSector','$this->nombre','$this->precio')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
}

?>