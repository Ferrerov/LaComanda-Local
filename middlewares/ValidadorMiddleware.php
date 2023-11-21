<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

include_once '../db/AccesoDatos.php';

class ValidadorMiddleware
{

    public function ValidarDatosUsuario(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();

        if(!preg_match('/^[a-zA-Z0-9]+$/', $parametros['nombreUsuario']))
        {
            $resultado = array_merge($resultado, array('errorNombre' => 'Nombre de usuario no valido, debe ser alfanumerico.'));
        }
        if(ValidadorMiddleware::ExisteNombreUsuario($parametros['nombreUsuario']) != false)
        {
            $resultado = array_merge($resultado, array('errorUsuarioExistente' => 'El nombre de usuario ya esta en uso.'));
        }
        if($parametros['contraseña'] == '')
        {
            $resultado = array_merge($resultado, array('errorContraseñaVacia' => 'La contraseña no puede estar vacia.'));
        }
        if(strtoupper($parametros['tipo']) != 'SOCIO' && strtoupper($parametros['tipo']) != 'EMPLEADO')
        {
            $resultado = array_merge($resultado, array('errorTipoSocio' => 'El usuario debe ser tipo SOCIO/EMPLEADO.'));
        }
        if(strtoupper($parametros['estado']) != 'ACTIVO' && strtoupper($parametros['estado']) != 'INACTIVO')
        {
            $resultado = array_merge($resultado, array('errorEstado' => 'El estado debe ser ACTIVO/INACTIVO.'));
        }
        if(count($resultado) != 0)
        {
            $response = new Response();
            $payload = json_encode($resultado);
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarDatosEmpleado(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();
        $resultado = array();

        if(ValidadorMiddleware::ExisteId($parametros['idUsuario'], 'idUsuario', 'empleados') != false)
        {
            $resultado = array_merge($resultado, array('errorIdUsuario' => 'El usuario ya tiene asignado un trabajador.'));
        }
        if(ValidadorMiddleware::ExisteId($parametros['idUsuario'], 'idUsuario', 'usuarios') == false)
        {
            $resultado = array_merge($resultado, array('errorIdUsuario' => 'No existe el usuario ingresado.'));
        }
        if(ValidadorMiddleware::ExisteId($parametros['idSector'], 'idSector', 'sectores') == false)
        {
            $resultado = array_merge($resultado, array('errorIdSector' => 'No existe el sector ingresado.'));
        }
        if(!preg_match('/[a-zA-Z]+/', $parametros['nombreEmpleado']))
        {
            $resultado = array_merge($resultado, array('errorNombre' => 'Nombre de usuario no valido, debe contener unicamente letras.'));
        }
        if(strtoupper($parametros['tipo']) != 'BARTENDER' && strtoupper($parametros['tipo']) != 'CERVECERO' && strtoupper($parametros['tipo']) != 'COCINERO' && strtoupper($parametros['tipo']) != 'MOZO')
        {
            $resultado = array_merge($resultado, array('errorTipoEmpleado' => 'El empleado debe ser tipo BARTENDER/CERVECERO/COCINERO/MOZO.'));
        }
        
        if(strtoupper($parametros['estado']) != 'ACTIVO' && strtoupper($parametros['estado']) != 'INACTIVO')
        {
            $resultado = array_merge($resultado, array('errorEstadoEmpleado' => 'El estado debe ser ACTIVO/INACTIVO.'));
        }
        if(count($resultado) != 0)
        {
            $response = new Response();
            $payload = json_encode($resultado);
            $response->getBody()->write($payload);
        }
        else
        {
            $response = $handler->handle($request);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    
    public function ValidarCredencialesUsuario(Request $request, RequestHandler $handler): Response
    {
        $parametros = $request->getParsedBody();

        if(ValidadorMiddleware::CredencialesValidas($parametros['usuario'], $parametros['contraseña']))
        {
            $response = $handler->handle($request);
        }
        else
        {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'No se encontro el usuario, verifique el usuario y contraseña.'));

            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    private static function ExisteNombreUsuario($nombreUsuario)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario'");
        $consulta->execute();
        return $consulta->fetchObject();
    }
    private static function ExisteId($idBuscar, $idBuscado, $tabla)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from $tabla where $idBuscado = '$idBuscar'");
        $consulta->execute();
        return $consulta->fetchObject();
    }
    private static function CredencialesValidas($nombreUsuario, $contraseña)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombreUsuario from usuarios where nombreUsuario = '$nombreUsuario' and contraseña = '$contraseña' and estado != 'INACTIVO'");

        $consulta->execute();
        return $consulta->fetchObject();
    }

}

?>