<?php

class Trabajador{
    public $idTrabajador;
    public $idUsuario;
    public $idSector;
    public $tipo;
    public $nombre;
    public $fechaIngreso;
    public $fechaEgreso;

    public function __construct() {}
    public static function ConstruirTrabajador($idUsuario, $idSector, $tipo, $nombre, $fechaIngreso, $fechaEgreso){
        
        $trabajador = new Trabajador();

        $trabajador->idUsuario = $idUsuario;
        $trabajador->idSector = $idSector;
        $trabajador->tipo = $tipo;
        $trabajador->nombre = $nombre;
        $trabajador->fechaIngreso = $fechaIngreso;
        $trabajador->fechaEgreso = $fechaEgreso;

        return $trabajador;
    }

}


?>