<?php

class Pedido
{
    public $idPedido;
    public $idMesa;
    public $idProducto;
    public $estado;
    public $precioVenta;
    public $tiempoEstimado;
    public $tiempoInicio;
    public $tiempoFin;

    public function __construct() {}
    public static function ConstruirPedido($idMesa, $idProducto, $estado, $precioVenta, $tiempoEstimado=null, $tiempoFin=null)
    {
        $pedido = new Pedido();

        $pedido->idMesa = $idMesa;
        $pedido->idProducto = $idProducto;
        $pedido->estado = strtoupper($estado); 
        $pedido->precioVenta = $precioVenta;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->tiempoInicio =  null;
        $pedido->tiempoFin = $tiempoFin;

        return $pedido;
    }

}

?>