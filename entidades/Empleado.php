<?php

require_once '../db/AccesoDatos.php';
class Empleado{
    public $idEmpleado;
    public $idUsuario;
    public $idSector;
    public $tipo;
    public $estado;
    public $fechaIngreso;
    public $fechaEgreso;

    public function __construct() {}
    public static function ConstruirEmpleado($idUsuario, $idSector, $tipo, $estado, $fechaEgreso=null){
        
        $empleado = new Empleado();

        $empleado->idUsuario = $idUsuario;
        $empleado->idSector = $idSector;
        $empleado->tipo = strtoupper($tipo);
        $empleado->estado = strtoupper($estado);
        $empleado->fechaIngreso = (new DateTime('now'))->format('Y-m-d H:i:s');
        $empleado->fechaEgreso = $fechaEgreso;

        return $empleado;
    }

}


?>