<?php

class Encuesta
{
    public $idEncuesta;
    public $idMesa;
    public $idPedido;
    public $puntuacionMesa;
    public $puntuacionRestaurante;
    public $puntuacionMozo;
    public $puntuacionCocinero;
    public $comentario;

    public function __construct() {}
    public static function ConstruirEncuesta($idMesa, $idPedido, $puntuacionMesa, $puntuacionRestaurante, $puntuacionMozo, $puntuacionCocinero, $comentario)
    {
        $encuesta = new Encuesta();

        $encuesta->idMesa = $idMesa;
        $encuesta->idPedido = $idPedido;
        $encuesta->puntuacionMesa = $puntuacionMesa;
        $encuesta->puntuacionRestaurante = strtoupper($puntuacionRestaurante); 
        $encuesta->puntuacionMozo = $puntuacionMozo;
        $encuesta->puntuacionCocinero = $puntuacionCocinero;
        $encuesta->comentario =  $comentario;

        return $encuesta;
    }

}

?>