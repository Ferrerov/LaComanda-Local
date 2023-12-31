<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once '../utilidades/GestorConsultas.php';
include_once '../utilidades/Validador.php';

class ValidadorMiddleware
{
    public function ValidarDatosUsuarioCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (Validador::ValidarAlfanumerico($parametros['nombreUsuario'])) {
                $resultado = array_merge($resultado, array('errorNombre' => 'Nombre de usuario no valido, debe ser alfanumerico.'));
            }
            if (GestorConsultas::ExisteNombreUsuario($parametros['nombreUsuario']) != false) {
                $resultado = array_merge($resultado, array('errorUsuarioExistente' => 'El nombre de usuario ya esta en uso.'));
            }
            if ($parametros['contraseña'] == '') {
                $resultado = array_merge($resultado, array('errorContraseñaVacia' => 'La contraseña no puede estar vacia.'));
            }
            if (!preg_match('/^[a-zA-Z]+$/', $parametros['nombre'])) {
                $resultado = array_merge($resultado, array('errorNombre' => 'Nombre de usuario no valido.'));
            }
            if (!preg_match('/^[a-zA-Z]+$/', $parametros['apellido'])) {
                $resultado = array_merge($resultado, array('errorApellido' => 'Apellido de usuario no valido.'));
            }
            if (strtoupper($parametros['tipo']) != 'SOCIO' && strtoupper($parametros['tipo']) != 'EMPLEADO') {
                $resultado = array_merge($resultado, array('errorTipoSocio' => 'El usuario debe ser tipo SOCIO/EMPLEADO.'));
            }
            if (strtoupper($parametros['estado']) != 'ACTIVO' && strtoupper($parametros['estado']) != 'INACTIVO') {
                $resultado = array_merge($resultado, array('errorEstado' => 'El estado debe ser ACTIVO/INACTIVO.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }


    public function ValidarDatosEmpleadoCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        try {
            if(!Validador::ValidarNumerico($parametros['idUsuario']) || GestorConsultas::ExisteId($parametros['idUsuario'], 'idUsuario', 'empleados') != false)  
            /*(!preg_match('/^[0-9]+$/', $parametros['idUsuario']) || GestorConsultas::ExisteId($parametros['idUsuario'], 'idUsuario', 'empleados') != false)*/{
                $resultado = array_merge($resultado, array('errorIdUsuario' => 'El usuario ya tiene asignado un trabajador.'));
            }
            if(Validador::ValidarNumerico($parametros['idUsuario']) || GestorConsultas::ExisteId($parametros['idUsuario'], 'idUsuario', 'usuarios') == false)
             /*(!preg_match('/^[0-9]+$/', $parametros['idUsuario']) || GestorConsultas::ExisteId($parametros['idUsuario'], 'idUsuario', 'usuarios') == false)*/ {
                $resultado = array_merge($resultado, array('errorIdUsuario' => 'No existe el usuario ingresado.'));
            }
            if (!preg_match('/^[0-9]+$/', $parametros['idSector']) || GestorConsultas::ExisteId($parametros['idSector'], 'idSector', 'sectores') == false) {
                $resultado = array_merge($resultado, array('errorIdSector' => 'No existe el sector ingresado.'));
            }
            if (strtoupper($parametros['tipo']) != 'BARTENDER' && strtoupper($parametros['tipo']) != 'CERVECERO' && strtoupper($parametros['tipo']) != 'COCINERO' && strtoupper($parametros['tipo']) != 'MOZO') {
                $resultado = array_merge($resultado, array('errorTipoEmpleado' => 'El empleado debe ser tipo BARTENDER/CERVECERO/COCINERO/MOZO.'));
            }

            if (strtoupper($parametros['estado']) != 'ACTIVO' && strtoupper($parametros['estado']) != 'INACTIVO') {
                $resultado = array_merge($resultado, array('errorEstadoEmpleado' => 'El estado debe ser ACTIVO/INACTIVO.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {

            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ValidarDatosProductoCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (!preg_match('/^[0-9]+$/', $parametros['idSector']) || GestorConsultas::ExisteId($parametros['idSector'], 'idSector', 'sectores') == false) {
                $resultado = array_merge($resultado, array('errorIdSector' => 'No existe el sector ingresado.'));
            }
            if (GestorConsultas::TraerProductoPorNombre($parametros['nombreProducto']) != false) {
                $resultado = array_merge($resultado, array('errorNombreProducto' => 'El producto ya existe.'));
            }
            if(!Validador::ValidarAlfanumerico($parametros['nombreProducto'])){
                $resultado = array_merge($resultado, array('errorNombreProducto' => 'Nombre de producto no valido, debe ser alfanumerico.'));
            }
            if (!preg_match('/^[0-9]+$/', $parametros['precio'])) {
                $resultado = array_merge($resultado, array('errorPrecio' => 'El precio debe ser mayor o igual a 0.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    /*if(($request->getUploadedFiles()['imagen'])->getError() != 0)
                {
                    $resultado = array_merge($resultado, array('errorImagen' => 'No se paso una imagen.'));
                }*/
    public function ValidarImagen(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (($request->getUploadedFiles()['imagen'])->getError() != 0) {
                $resultado = array_merge($resultado, array('errorImagen' => 'No se paso una imagen.'));
            }
            if(GestorConsultas::ExisteId($parametros['idPedido'], 'idPedido', 'pedidos') == false)
            {
                $resultado = array_merge($resultado, array('errorIdPedido' => 'El id del pedido no existe.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ValidarDatosMesaCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (!preg_match('/^[0-9]+$/', $parametros['idEmpleado']) || GestorConsultas::ExisteId($parametros['idEmpleado'], 'idEmpleado', 'empleados') == false) {
                $resultado = array_merge($resultado, array('errorIdEmpleado' => 'No existe el empleado ingresado.'));
            }
            if (!preg_match('/^[a-zA-Z]+$/', $parametros['nombreCliente'])) {
                $resultado = array_merge($resultado, array('errorNombreCliente' => 'Nombre de cliente no valido.'));
            }
            if (strtoupper($parametros['estado']) != 'CON CLIENTE ESPERANDO PEDIDO' && strtoupper($parametros['estado']) != 'CON CLIENTE COMIENDO' && strtoupper($parametros['estado']) != 'CON CLIENTE PAGANDO' && strtoupper($parametros['estado']) != 'CERRADA') {
                $resultado = array_merge($resultado, array('errorEstadoMesa' => 'Los estados de la mesa deben ser CON CLIENTE ESPERANDO PEDIDO/CON CLIENTE COMIENDOO/CON CLIENTE PAGANDO/CERRADA.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    public function ValidarDatosMesaModificacion(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $tipoEmpleado = $request->getAttribute('tipoEmpleado');
            if (GestorConsultas::ExisteId($parametros['idMesa'], 'idMesa', 'mesas') == false) {
                $resultado = array_merge($resultado, array('errorIdMesa' => 'No existe la mesa ingresada.'));
            }
            if (isset($parametros['idEmpleado']) && GestorConsultas::ExisteId($parametros['idEmpleado'], 'idEmpleado', 'empleados') == false) {
                $resultado = array_merge($resultado, array('errorIdEmpleado' => 'No existe el empleado ingresado.'));
            }
            if (isset($parametros['nombreCliente']) && Validador::ValidarAlfa($parametros['nombreCliente']) == false) {
                $resultado = array_merge($resultado, array('errorNombreCliente' => 'Nombre de cliente no valido.'));
            }
            if($tipoEmpleado == 'SOCIO')
            {
                if(isset($parametros['estado']) && Validador::ValidarArray(strtoupper($parametros['estado']), array('CON CLIENTE ESPERANDO PEDIDO', 'CON CLIENTE COMIENDO', 'CON CLIENTE PAGANDO', 'CERRADA')) == false)
                {
                    $resultado = array_merge($resultado, array('errorEstadoMesa' => 'Los estados de la mesa deben ser CON CLIENTE ESPERANDO PEDIDO/CON CLIENTE COMIENDOO/CON CLIENTE PAGANDO/CERRADA.'));
                }
            }
            else
            {
                if(isset($parametros['estado']) && Validador::ValidarArray(strtoupper($parametros['estado']), array('CON CLIENTE ESPERANDO PEDIDO', 'CON CLIENTE COMIENDO', 'CON CLIENTE PAGANDO')) == false)
                {
                    $resultado = array_merge($resultado, array('errorEstadoMesa' => 'Los estados de la mesa deben ser CON CLIENTE ESPERANDO PEDIDO/CON CLIENTE COMIENDOO/CON CLIENTE PAGANDO.'));
                }
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ValidarDatosPedidoCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (!preg_match('/^[0-9]+$/', $parametros['idMesa']) || GestorConsultas::ExisteId($parametros['idMesa'], 'idMesa', 'mesas') == false) {
                $resultado = array_merge($resultado, array('errorIdMesa' => 'No existe la mesa ingresada.'));
            }
            if (!preg_match('/^[0-9]+$/', $parametros['idProducto']) || GestorConsultas::ExisteId($parametros['idProducto'], 'idProducto', 'productos') == false) {
                $resultado = array_merge($resultado, array('errorIdProducto' => 'No existe el producto ingresado.'));
            }
            if (strtoupper($parametros['estado']) != 'PENDIENTE' && strtoupper($parametros['estado']) != 'EN PREPARACION' && strtoupper($parametros['estado']) != 'LISTO PARA SERVIR' && strtoupper($parametros['estado']) != 'ENTREGADO') {
                $resultado = array_merge($resultado, array('errorEstadoPedido' => 'Los estados del pedido deben ser PENDIENTE/EN PREPARACION/LISTO PARA SERVIR/ENTREGADO.'));
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ValidarDatosPedidoModificacion(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $idPedido = $parametros['idPedido'];
            $estado = $parametros['estado'];
            if (isset($idPedido) && isset($estado)) {
                $request = $request->withAttribute('tipoModificacion', 'cambioEstado');
                if (isset($parametros['tiempoEstimado'])) {
                    $request = $request->withAttribute('tipoModificacion', 'cambioEstadoTiempo');
                    if (!ValidadorMiddleware::ValidarFecha($parametros['tiempoEstimado'], 'H:i:s')) {
                        $resultado = array_merge($resultado, array('errorTiempoEstimado' => "El tiempo estimado del pedido debe tener formato 'H:i:s.'"));
                    }
                }
                if (isset($parametros['idMesa']) && isset($parametros['idProducto'])) {
                    $request = $request->withAttribute('tipoModificacion', 'cambioDatos');
                    if (!preg_match('/^[0-9]+$/', $parametros['idMesa']) || GestorConsultas::ExisteId($parametros['idMesa'], 'idMesa', 'mesas') == false) {
                        $resultado = array_merge($resultado, array('errorIdMesa' => 'No existe la mesa ingresada.'));
                    }
                    if (!preg_match('/^[0-9]+$/', $parametros['idProducto']) || GestorConsultas::ExisteId($parametros['idProducto'], 'idProducto', 'productos') == false) {
                        $resultado = array_merge($resultado, array('errorIdProducto' => 'No existe el producto ingresado.'));
                    }
                }
                if (!preg_match('/^[0-9]+$/', $parametros['idPedido']) || GestorConsultas::ExisteId($parametros['idPedido'], 'idPedido', 'pedidos') == false) {
                    $resultado = array_merge($resultado, array('errorIdPedido' => 'No existe el pedido ingresado.'));
                }
                if (strtoupper($parametros['estado']) != 'PENDIENTE' && strtoupper($parametros['estado']) != 'EN PREPARACION' && strtoupper($parametros['estado']) != 'LISTO PARA SERVIR' && strtoupper($parametros['estado']) != 'ENTREGADO') {
                    $resultado = array_merge($resultado, array('errorEstadoPedido' => 'Los estados del pedido deben ser PENDIENTE/EN PREPARACION/LISTO PARA SERVIR/ENTREGADO.'));
                }
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {
            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    public function ValidarDatosEncuestaCarga(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
        try {

            $pedido = GestorConsultas::TraerUnPedidoPorIdPedidoCodigoDeMesa($parametros['idPedido'], $parametros['codigo']);
            if($pedido == false)  
            {
                $resultado = array_merge($resultado, array('errorDatos' => 'El codigo de mesa y el id del pedido no coinciden.'));
            }
            else
            {
                $mesa = GestorConsultas::TraerUnaMesa($pedido->idMesa);
                if($mesa->estado != 'CERRADA')
                {
                    $resultado = array_merge($resultado, array('errorEstado' => 'La mesa aun no esta cerrada.'));
                }
                else
                {
                    if(GestorConsultas::TraerUnaEncuestaPorIdMesaIdPedido($mesa->idMesa, $parametros['idPedido']) != false)
                    {
                        $resultado = array_merge($resultado, array('errorEncuesta' => 'La encuesta para esta mesa y este pedido ya esta completada.'));
                    }
                    if(Validador::ValidarArray($parametros['puntuacionMesa'], array(0,1,2,3,4,5,6,7,8,9,10)) == false){
                        $resultado = array_merge($resultado, array('errorPuntuacionMesa' => 'La puntuacion debe ser del 1 al 10.'));
                    }
                    if(Validador::ValidarArray($parametros['puntuacionRestaurante'], array(0,1,2,3,4,5,6,7,8,9,10)) == false){
                        $resultado = array_merge($resultado, array('errorPuntuacionRestaurante' => 'La puntuacion debe ser del 1 al 10.'));
                    }
                    if(Validador::ValidarArray($parametros['puntuacionMozo'], array(0,1,2,3,4,5,6,7,8,9,10)) == false){
                        $resultado = array_merge($resultado, array('errorPuntuacionMozo' => 'La puntuacion debe ser del 1 al 10.'));
                    }
                    if(Validador::ValidarArray($parametros['puntuacionCocinero'], array(0,1,2,3,4,5,6,7,8,9,10)) == false){
                        $resultado = array_merge($resultado, array('errorPuntuacionCocinero' => 'La puntuacion debe ser del 1 al 10.'));
                    }
                    if(Validador::ValidarAlfa($parametros['comentario']) == false || strlen($parametros['comentario']) > 66){
                        $resultado = array_merge($resultado, array('errorComentario' => 'No se admiten numeros ni caracteres y la longitud maxima es de 66.'));
                    }
                }
            }
        } catch (ErrorException $e) {
            $resultado = array_merge($resultado, array('errorParametros' => 'No se pasaron todos los parametros requeridos.'));
            $resultado = array_merge($resultado, array('errorDetalle' => $e->getMessage()));
        } finally {

            if (count($resultado) != 0) {
                $response = new Response();
                $payload = json_encode($resultado);
                $response->getBody()->write($payload);
            } else {
                $response = $handler->handle($request);
            }
            restore_error_handler();
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function ValidarCredencialesUsuario(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        if (GestorConsultas::CredencialesValidas($parametros['usuario'], $parametros['contraseña'])) {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No se encontro el usuario, verifique el usuario y contraseña.'));

            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    private static function ValidarFecha($fecha, $formato = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($formato, $fecha);
        return $d && $d->format($formato) == $fecha;
    }
}
