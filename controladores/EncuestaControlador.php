<?php

require_once '../entidades/Encuesta.php';
require_once '../utilidades/GestorConsultas.php';

class EncuestaControlador
{
    public function TraerUno($request, $response, $args)
    {
        $parametros = $request->getQueryParams();
        $mesa = GestorConsultas::TraerUnaMesa($parametros['idMesa']);
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

        $mesa = GestorConsultas::TraerUnaMesaPorCodigo($parametros['codigo']);
        $encuesta = Encuesta::ConstruirEncuesta($mesa->idMesa, $parametros['idPedido'],$parametros['puntuacionMesa'],$parametros['puntuacionRestaurante'],$parametros['puntuacionMozo'],$parametros['puntuacionCocinero'],$parametros['comentario'],);

        $idEncuesta = GestorConsultas::CargarUnaEncuesta($encuesta);
        $encuesta->idEncuesta = $idEncuesta;
        if($idEncuesta > 0)
        {
            $payload = array_merge(array('mensaje' => 'Encuesta cargada correctamente'),(array)$encuesta);
            $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo cargar la encuesta.'));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $mesaActualizada = 0;

        $mesa = GestorConsultas::TraerUnaMesa($parametros['idMesa']);
        if($mesa != null)
        {
            if(isset($parametros['idEmpleado'])) $mesa->idEmpleado = $parametros['idEmpleado'];
            if(isset($parametros['nombreCliente'])) $mesa->nombreCliente = $parametros['nombreCliente'];
            if(isset($parametros['estado'])) $mesa->estado = $parametros['estado'];

            $mesaActualizada = GestorConsultas::ActualizarUnaMesa($mesa);
        }

        if($mesaActualizada > 0)
        {
            $payload = array_merge(array('mensaje' => 'Mesa actualizada correctamente'),(array)$mesa);
            $payload = json_encode($payload);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se pudo actualizar la mesa.'));
        }   
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args)
    {

    }
    public function TraerTodos($request, $response, $args)
    {
        if(isset($args['tipo']))
        {
            $encuestas = GestorConsultas::TraerLosMejoresComentarios();
            if($encuestas != null)
            {
                $encuestasArray = array();
                foreach($encuestas as $unaEncuesta)
                {
                    array_push($encuestasArray, array('idEncuesta' => $unaEncuesta->idEncuesta, 'comentario' => $unaEncuesta->comentario));
                }
                $encuestas = $encuestasArray;
            }
        }else $encuestas = GestorConsultas::TraerTodasLasEncuestas();
        if($encuestas != null)
        {
            $payload = json_encode($encuestas);
        }
        else
        {
            $payload = json_encode(array('mensaje' => 'No se encontraron encuestas.'));
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