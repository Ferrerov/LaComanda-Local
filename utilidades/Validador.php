<?php

class Validador
{
    private static function ValidarConRegex($valor, $regex)
    {
        if(preg_match($regex, $valor)) return true;
        return false;
    }

    public static function ValidarAlfa($valor)
    {
        return self::ValidarConRegex($valor, '/^[a-zA-Z ]+$/');
    }
    public static function ValidarNumerico($valor)
    {
        return self::ValidarConRegex($valor, '/^[0-9]+$/');
    }
    public static function ValidarAlfanumerico($valor)
    {
        return self::ValidarConRegex($valor, '/^[a-zA-Z0-9 ]+$/');
    }
    public static function ValidarArray($valor, $valoresValidos)
    {   
        if(in_array(strtoupper($valor), $valoresValidos)) return true;
        return false;
    }

    public static function ValidarFecha($fecha, $formato = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($formato, $fecha);
        return $d && $d->format($formato) == $fecha;
    }
}


?>