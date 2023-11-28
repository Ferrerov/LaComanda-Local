<?php
require_once '../db/AccesoDatos.php';
class Usuario{
    public $idUsuario;
    public $nombreUsuario;
    public $contraseña;
    public $nombre;
    public $apellido;
    public $tipo;
    public $estado;
    public $fechaCreacion;

    public function __construct() {}
    public static function ConstruirUsuario($nombreUsuario, $contraseña, $nombre, $apellido, $tipo, $estado='Activo')
    {
        $usuario = new Usuario();

        $usuario->nombreUsuario = $nombreUsuario;
        $usuario->contraseña = $contraseña;
        $usuario->nombre = ucwords($nombre);
        $usuario->apellido = ucwords($apellido);
        $usuario->tipo = strtoupper($tipo);
        $usuario->estado = strtoupper($estado);
        $usuario->fechaCreacion = (new DateTime('now'))->format('Y-m-d H:i:s');

        return $usuario;
    }

}

?>