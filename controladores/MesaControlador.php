<?php

require_once '../entidades/Mesa.php';
require_once '../interfaces/IApiUsable.php';
require_once '../utilidades/GestorConsultas.php';

class MesaControlador implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $idMesa = $args['idMesa'];
        $mesa = GestorConsultas::TraerUnaMesa($idMesa);
        if($mesa != null)
        {
            $payload = json_encode($mesa);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontro la mesa.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = self::GenerarCodigoAlfanumerico();
        $mesa = Mesa::ConstruirMesa($parametros['idEmpleado'], $codigo,$parametros['nombreCliente'],$parametros['estado']);

        $idMesa = GestorConsultas::CargarUnaMesa($mesa);
        $mesa->idMesa = $idMesa;
        if($idMesa > 0)
        {
            $payload = array_merge(array('mensaje' => 'Mesa cargada correctamente'),(array)$mesa);
            $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar la mesa.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {

    }
    public function BorrarUno($request, $response, $args)
    {

    }
    public function TraerTodos($request, $response, $args)
    {
        $mesas = GestorConsultas::TraerTodasLasMesas();
        if($mesas != null)
        {
            $payload = json_encode($mesas);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron mesas.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function GenerarCodigoAlfanumerico()
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do{
            $codigo = substr(str_shuffle($caracteres), 0, 5);
        }while(GestorConsultas::ExisteCodigo($codigo) != false);
        return $codigo;
    }
}

?>