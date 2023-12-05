<?php

include_once '../db/AccesoDatos.php';
include_once '../entidades/Encuesta.php';

class GestorConsultas
{
    public static function ExisteId($idBuscar, $idBuscado, $tabla)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from $tabla where $idBuscado = '$idBuscar'");
        $consulta->execute();
        return $consulta->fetchObject();
    }
    public static function CredencialesValidas($nombreUsuario, $contraseña)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario' and contraseña = '$contraseña' and estado != 'INACTIVO'");

        $consulta->execute();
        return $consulta->fetchObject();
    }

    //CONSULTAS USUARIOS
    
    public static function ExisteNombreUsuario($nombreUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario'");
        $consulta->execute();
        return $consulta->fetchObject();
    }
    public static function TraerUnUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idUsuario, nombreUsuario, contraseña, tipo, estado, fechaCreacion from usuarios where idUsuario = '$idUsuario' and estado = 'ACTIVO'");
        $consulta->execute();
        $usuarioBuscado = $consulta->fetchObject('usuario');
        return $usuarioBuscado;
    }

    public static function TraerUnUsuarioPorNombreUsuario($nombreUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idUsuario, nombreUsuario, contraseña, tipo from usuarios where nombreUsuario = '$nombreUsuario' and estado = 'ACTIVO'");
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

    public static function CargarUnUsuario($usuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombreUsuario, contraseña, tipo, estado, fechaCreacion) values('$usuario->nombreUsuario','$usuario->contraseña','$usuario->tipo','$usuario->estado','$usuario->fechaCreacion')");
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


    //CONSULTAS PRODUCTOS
    public static function TraerProductoPorNombre($nombre)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idProducto, idSector, nombreProducto, precio from productos where nombreProducto = '$nombre'");
        $consulta->execute();
        $productoBuscado = $consulta->fetchObject('producto');
        return $productoBuscado;
    }

    public static function TraerUnProducto($idProducto)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idProducto, idSector, nombreProducto, precio from productos where idProducto = '$idProducto'");
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

    public static function CargarUnProducto($producto)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into productos (idSector, nombreProducto, precio) values('$producto->idSector','$producto->nombreProducto','$producto->precio')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    //CONSULTAS EMPLEADOS
    public static function TraerUnEmpleado($idEmpleado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idEmpleado, idUsuario, idSector, tipo, estado, fechaIngreso, fechaEgreso from empleados where idEmpleado = '$idEmpleado' and estado = 'ACTIVO'");
        $consulta->execute();
        $usuarioBuscado = $consulta->fetchObject('empleado');
        return $usuarioBuscado;
    }
    public static function CargarUnEmpleado($empleado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into empleados (idUsuario, idSector, tipo, estado, fechaIngreso, fechaEgreso) values('$empleado->idUsuario','$empleado->idSector','$empleado->tipo','$empleado->estado','$empleado->fechaIngreso','$empleado->fechaEgreso')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function TraerUnEmpleadoPorIdUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from empleados where idUsuario = '$idUsuario' and estado = 'ACTIVO'");
        $consulta->execute();
        $empleadoBuscado = $consulta->fetchObject('empleado');
        return $empleadoBuscado;
    }

    public static function BorrarUnEmpleado($idEmpleado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE empleados SET estado = 'INACTIVO' WHERE idEmpleado = '$idEmpleado'");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function TraerTodosLosEmpleados()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from empleados");
        $consulta->execute();
        $empleadosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'empleado');
        return $empleadosBuscados;
    }

    //CONSULTAS PEDIDOS
    public static function TraerUnPedido($idPedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from pedidos where idPedido = '$idPedido'");
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
    public static function TraerTodosLosPedidosPorEstado($estado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from pedidos where estado = '$estado'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }
    public static function TraerTiempoDeTodosLosPedidos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idPedido, tiempoEstimado from pedidos");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }
    public static function TraerTodosLosPedidosPendientes()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from pedidos where estado = 'PENDIENTE'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }

    public static function CargarUnPedido($pedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into pedidos (idMesa, idProducto, estado, precioVenta, tiempoEstimado, tiempoInicio, tiempoFin) values('$pedido->idMesa','$pedido->idProducto','$pedido->estado', '$pedido->precioVenta', '$pedido->tiempoEstimado','$pedido->tiempoInicio','$pedido->tiempoFin')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function BorrarTodosLosPedidos()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("TRUNCATE TABLE pedidos");
        $consulta->execute();
        return $consulta->rowCount();
    }

    public static function TraerTodosLosPedidosPorIdUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idPedido, p.idMesa, p.idProducto, p.estado, p.precioVenta, p.tiempoEstimado, p.tiempoInicio, p.tiempoFin FROM pedidos p JOIN productos ON p.idProducto = productos.idProducto JOIN empleados ON empleados.idSector = productos.idSector WHERE empleados.idUsuario = '$idUsuario'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }

    public static function TraerTodosLosPedidosPorIdUsuarioYEstado($idUsuario, $estado)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idPedido, p.idMesa, p.idProducto, p.estado, p.precioVenta, p.tiempoEstimado, p.tiempoInicio, p.tiempoFin FROM pedidos p JOIN productos ON p.idProducto = productos.idProducto JOIN empleados ON empleados.idSector = productos.idSector WHERE empleados.idUsuario = '$idUsuario' and p.estado = '$estado'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }

    public static function TraerTodosLosTiemposDePedidosPorIdUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idPedido, p.tiempoEstimado FROM pedidos p JOIN productos ON p.idProducto = productos.idProducto JOIN empleados ON empleados.idSector = productos.idSector WHERE empleados.idUsuario = '$idUsuario'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }
    public static function TraerTodosLosPedidosPendientesPorIdUsuario($idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idPedido, p.idMesa, p.idProducto, p.estado, p.precioVenta, p.tiempoEstimado, p.tiempoInicio, p.tiempoFin FROM pedidos p JOIN productos ON p.idProducto = productos.idProducto JOIN empleados ON empleados.idSector = productos.idSector WHERE empleados.idUsuario = '$idUsuario' and p.estado = 'PENDIENTE'");
        $consulta->execute();
        $pedidosBuscados = $consulta->fetchAll(PDO::FETCH_CLASS,'pedido');
        return $pedidosBuscados;
    }

    public static function TraerUnPedidoPorIdPedidoIdUsuario($idPedido, $idUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.idPedido, p.idMesa, p.idProducto, p.estado, p.precioVenta, p.tiempoEstimado, p.tiempoInicio, p.tiempoFin FROM pedidos p JOIN productos ON p.idProducto = productos.idProducto JOIN empleados ON empleados.idSector = productos.idSector WHERE empleados.idUsuario = '$idUsuario' AND p.idPedido = '$idPedido'");
        $consulta->execute();
        $pedidoBuscado = $consulta->fetchObject('pedido');
        return $pedidoBuscado;
    }

    public static function ActualizarUnPedido($pedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $estado = strtoupper($pedido->estado);
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE pedidos SET 
        idMesa = '$pedido->idMesa', idProducto = '$pedido->idProducto', estado = '$estado', precioVenta = '$pedido->precioVenta', tiempoEstimado = '$pedido->tiempoEstimado', tiempoInicio = '$pedido->tiempoInicio', tiempoFin = '$pedido->tiempoFin'
      WHERE idPedido = '$pedido->idPedido'");
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function TraerUnPedidoPorIdPedidoCodigoDeMesa($idPedido, $codigoMesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM pedidos p JOIN mesas m ON p.idMesa = m.idMesa WHERE p.idPedido = '$idPedido' AND m.codigo = '$codigoMesa'");
        $consulta->execute();
        $pedidoBuscado = $consulta->fetchObject();
        return $pedidoBuscado;
    }

    //CONSULTAS MESAS
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
        $consulta = $objetoAccesoDato->RetornarConsulta("select idMesa, idEmpleado, codigo, nombreCliente, estado from mesas where idMesa = '$idMesa'");
        $consulta->execute();
        $mesaBuscada = $consulta->fetchObject('mesa');
        return $mesaBuscada;
    }
    public static function TraerUnaMesaPorCodigo($codigo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select * from mesas where codigo = '$codigo'");
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

    public static function TraerTodasLasMesasConEstado()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("select idMesa, estado from mesas");
        $consulta->execute();
        $mesasBuscadas = $consulta->fetchAll(PDO::FETCH_CLASS,'mesa');
        return $mesasBuscadas;
    }


    public static function CargarUnaMesa($mesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into mesas (idEmpleado, codigo, nombreCliente, estado) values('$mesa->idEmpleado','$mesa->codigo','$mesa->nombreCliente', '$mesa->estado')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function ActualizarUnaMesa($mesa)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $estado = strtoupper($mesa->estado);
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesas SET 
        idEmpleado = '$mesa->idEmpleado', nombreCliente = '$mesa->nombreCliente', estado = '$estado' WHERE idMesa = '$mesa->idMesa'");
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function TraerMesaMasUsada()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT mesas.* FROM pedidos JOIN Mesas ON pedidos.idMesa = mesas.idMesa GROUP BY pedidos.idMesa ORDER BY COUNT(pedidos.idMesa) DESC LIMIT 1");
        $consulta->execute();
        $mesasBuscada = $consulta->fetchAll(PDO::FETCH_CLASS,'mesa');
        return $mesasBuscada;
    }

    

    //CONSULTAS ENCUESTAS

    public static function CargarUnaEncuesta($encuesta)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT into encuestas (idMesa, idPedido, puntuacionMesa, puntuacionRestaurante, puntuacionMozo, puntuacionCocinero, comentario) values('$encuesta->idMesa', '$encuesta->idPedido','$encuesta->puntuacionMesa','$encuesta->puntuacionRestaurante', '$encuesta->puntuacionMozo', '$encuesta->puntuacionCocinero', '$encuesta->comentario')");
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function TraerUnaEncuestaPorIdMesaIdPedido($idMesa, $idPedido)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from encuestas where idMesa = '$idMesa' and idPedido = '$idPedido'");
        $consulta->execute();
        $mesaBuscada = $consulta->fetchObject();
        return $mesaBuscada;
    }

    public static function TraerTodasLasEncuestas()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from encuestas");
        $consulta->execute();
        $mesasBuscadas = $consulta->fetchAll(PDO::FETCH_CLASS,'encuesta');
        return $mesasBuscadas;
    }

    public static function TraerLosMejoresComentarios()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from encuestas where puntuacionMesa > 5 and puntuacionRestaurante > 5 and puntuacionMozo > 5 and puntuacionCocinero > 5 ORDER BY puntuacionMesa DESC, puntuacionRestaurante DESC, puntuacionMozo DESC, puntuacionCocinero DESC");
        $consulta->execute();
        $mesasBuscadas = $consulta->fetchAll(PDO::FETCH_CLASS,'encuesta');
        return $mesasBuscadas;
    }
}

?>