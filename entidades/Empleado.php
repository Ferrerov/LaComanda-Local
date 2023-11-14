<?php

class Empleado{
    public $idEmpleado;
    public $idUsuario;
    public $idSector;
    public $tipo;
    public $nombre;
    public $estado;
    public $fechaIngreso;
    public $fechaEgreso;

    public function __construct() {}
    public static function ConstruirEmpleado($idUsuario, $idSector, $tipo, $nombre, $estado, $fechaIngreso=null, $fechaEgreso=null){
        
        $empleado = new Empleado();

        $empleado->idUsuario = $idUsuario;
        $empleado->idSector = $idSector;
        $empleado->tipo = $tipo;
        $empleado->nombre = $nombre;
        $empleado->estado = $estado;
        $empleado->fechaIngreso = (new DateTime('now'))->format('d/m/Y H:i:s');
        $empleado->fechaEgreso = $fechaEgreso;

        return $empleado;
    }

    public function CargarUnEmpleado()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleados (idUsuario, idSector, tipo, nombre, estado, fechaIngreso, fechaEgreso) values('$this->idUsuario','$this->idSector','$this->tipo','$this->nombre','$this->estado','$this->fechaIngreso','$this->fechaEgreso')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

}


?>