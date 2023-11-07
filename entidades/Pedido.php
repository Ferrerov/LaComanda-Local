<?php

class Pedido
{
    public $idPedido;
    public $idMesa;
    public $idMozo;
    public $idProducto;
    public $estado;
    public $tiempoEstimado;
    public $tiempoInicio;
    public $tiempoFin;
    public $precioVenta;

    public function __construct() {}
    public static function ConstruirPedido($idMesa, $idMozo, $idProducto, $estado, $tiempoEstimado, $tiempoInicio, $tiempoFin, $precioVenta)
    {
        $pedido = new Pedido();

        $pedido->idMesa = $idMesa;
        $pedido->idMozo = $idMozo;
        $pedido->idProducto = $idProducto;
        $pedido->estado = $estado;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->tiempoInicio = $tiempoInicio;
        $pedido->tiempoFin = $tiempoFin;
        $pedido->precioVenta = $precioVenta;

        return $pedido;
    }

    public function MostrarDatos()
    {
        return '----------------------------------------------</br>
            Id de la mesa:' . $this->idMesa . '</br>
            Id del mozo: '. $this->idMozo . '</br>
            Id del producto: ' . $this->idProducto . '</br>
            Estado del pedido: ' . $this->estado . '</br>
            Tiempo estimado: ' . $this->tiempoEstimado . '</br>
            Tiempo de inicio: ' . $this->tiempoInicio . '</br>
            Tiempo de finalizacion: ' . $this->tiempoFin . '</br>
            Precio de venta: ' . $this->precioVenta . '</br>
            ----------------------------------------------</br>';
    }

    public static function TraerUnPedido($idPedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idPedido, idMesa, idProducto, estado, tiempoEstimado, tiempoInicio, tiempoFin, precioVenta from pedidos where idPedido = $idPedido");
        $consulta->execute();
        $pedidoBuscado = $consulta->fetchObject('pedido');
        return $pedidoBuscado;
    }

    public static function TraerTodosLosPedidos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from pedidos");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }

    public function CargarUnPedido()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into pedidos (idMesa, idProducto, estado, tiempoEstimado, tiempoInicio, tiempoFin, precioVenta) values('$this->idMesa','$this->idProducto','$this->estado','$this->tiempoEstimado','$this->tiempoInicio','$this->tiempoFin','$this->precioVenta')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
}

?>