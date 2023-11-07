<?php
require_once '../db/AccesoDatos.php';
class Usuario{
    public $idUsuario;
    public $nombreUsuario;
    public $contraseña;
    public $tipo;
    public $estado;
    public $fechaCreacion;

    public function __construct() {}
    public static function ConstruirUsuario($nombreUsuario, $contraseña, $tipo, $estado='Activo')
    {
        $usuario = new Usuario();

        $usuario->nombreUsuario = $nombreUsuario;
        $usuario->contraseña = $contraseña;
        $usuario->tipo = strtoupper($tipo);
        $usuario->estado = strtoupper($estado);
        $usuario->fechaCreacion = (new DateTime('now'))->format('d/m/Y H:i:s');

        return $usuario;
    }

    public function MostrarDatos()
    {
        return '----------------------------------------------</br>
            Nombre de usuario:' . $this->nombreUsuario . '</br>
            Contraseña: '. $this->contraseña . '</br>
            Tipo de usuario: ' . $this->tipo . '</br>
            Estado del usuario: ' . $this->estado . '</br>
            Fecha de creacion: ' . $this->fechaCreacion . '</br>
            ----------------------------------------------</br>';
    }

    public static function TraerUnUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idUsuario, nombreUsuario, contraseña, tipo, estado, fechaCreacion from usuarios where idUsuario = $idUsuario and estado = 'ACTIVO'");
        $consulta->execute();
        $usuarioBuscado = $consulta->fetchObject('usuario');
        return $usuarioBuscado;
    }

    public static function TraerTodosLosUsuarios()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from usuarios where estado = 'ACTIVO'");
        $consulta->execute();
        $usuariosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'usuario');
        return $usuariosBuscados;
    }

    public function CargarUnUsuario()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombreUsuario, contraseña, tipo, estado, fechaCreacion) values('$this->nombreUsuario','$this->contraseña','$this->tipo','$this->estado','$this->fechaCreacion')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function BorrarUnUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET estado = 'INACTIVO' WHERE idUsuario = '$idUsuario'");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function ExisteNombreUsuario($nombreUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario'");
        $consulta->execute();
        return $consulta->fetchObject();
    }

    public static function ValidarInput($nombreUsuario, $contraseña, $tipo, $estado)
    {
        $resultado = '';

        if(!preg_match('/^[a-zA-Z0-9]+$/', $nombreUsuario))
        {
            $resultado = $resultado . 'Nombre de usuario no valido, debe ser alfanumerico.</br>';
        }
        if(Usuario::ExisteNombreUsuario($nombreUsuario) != false)
        {
            $resultado = $resultado . 'El nombre de usuario ya esta en uso.</br>';
        }
        if($contraseña == '')
        {
            $resultado = $resultado . 'La contraseña no puede estar vacia.</br>';
        }
        if(strtoupper($tipo) != 'SOCIO' && strtoupper($tipo) != 'EMPLEADO')
        {
            $resultado = $resultado . 'El usuario debe ser tipo SOCIO/EMPLEADO.</br>';
        }
        if(strtoupper($estado) != 'ACTIVO' && strtoupper($estado) != 'INACTIVO')
        {
            $resultado = $resultado . 'El estado debe ser ACTIVO/INACTIVO.</br>';
        }

        return $resultado;
    }
}

?>