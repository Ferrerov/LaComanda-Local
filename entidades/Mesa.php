<?php

class Mesa{
    public $idMesa;
    public $idTrabajador;
    public $codigo;
    public $nombreCliente;
    public $estado;

    public function __construct() {}
    public static function ConstruirMesa($idTrabajador, $nombreCliente, $estado)
    {
        $mesa = new Mesa();

        $mesa->idTrabajador = $idTrabajador;
        $mesa->codigo = Mesa::GenerarCodigoAlfanumerico();
        $mesa->nombreCliente = $nombreCliente;
        $mesa->estado = $estado;

        return $mesa;
    }
    public function MostrarDatos()
    {
        return '----------------------------------------------</br>
            Id del trabajador:' . $this->idTrabajador . '</br>
            Codigo de la mesa:' . $this->codigo . '</br>
            Nombre del Cliente: '. $this->nombreCliente . '</br>
            Estado de la mesa: ' . $this->estado . '</br>
            ----------------------------------------------</br>';
    }

    public static function GenerarCodigoAlfanumerico()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do{
            $codigo = substr(str_shuffle($caracteres), 0, 5);
        }while(Mesa::ExisteCodigo($codigo) != false);
        return $codigo;
    }
    public static function ExisteCodigo($codigo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT codigo from mesas where codigo = '$codigo'");
        $consulta->execute();
        return $consulta->fetchObject();
    }

    public static function TraerUnaMesa($idMesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idMesa, idTrabajador, codigo, nombreCliente, estado from mesas where idMesa = $idMesa");
        $consulta->execute();
        $mesaBuscada = $consulta->fetchObject('mesa');
        return $mesaBuscada;
    }

    public static function TraerTodasLasMesas()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from mesas");
        $consulta->execute();
        $mesasBuscadas = $consulta->fetchAll(PDO::FETCH_CLASS,'mesa');
        return $mesasBuscadas;
    }

    public function CargarUnaMesa()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into mesas (idTrabajador, codigo, nombreCliente, estado) values('$this->idTrabajador','$this->codigo','$this->nombreCliente', '$this->estado')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
}

?>