<?php

class Mesa{
    public $idMesa;
    public $idEmpleado;
    public $codigo;
    public $nombreCliente;
    public $estado;

    public function __construct() {}
    public static function ConstruirMesa($idEmpleado, $codigo, $nombreCliente, $estado)
    {
        $mesa = new Mesa();

        $mesa->idEmpleado = $idEmpleado;
        $mesa->codigo = $codigo;
        $mesa->nombreCliente = $nombreCliente;
        $mesa->estado = strtoupper($estado);

        return $mesa;
    }
}

?>