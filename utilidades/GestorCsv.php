<?php

class GestorCsv
{
    public static function GuardarCsv($array, $ruta)
    {
        $primerDato = true;
        $datos = '';
        try {
            $archivo = fopen($ruta, "a");
            if ($archivo) {
                foreach ($array as $unCampo) {
                    if($primerDato)
                    {
                        $datos = $unCampo;
                        $primerDato = false;
                    }
                    else
                    {
                        $datos = $datos . "," . $unCampo;
                        $primerDato = false;
                    }
                }
                $datos =  $datos . PHP_EOL;
                fwrite($archivo, $datos);
            }
        } catch (\Throwable) {
            echo "Error";
        }finally{
            fclose($archivo);
        }
    }

    /*
    public static function LeerCsv($ruta)
    {
        $primerDato = true;
        $datos = '';
        try {
            $archivo = fopen($ruta, "r");
            if ($archivo) {
                foreach ($array as $unCampo) {
                    if($primerDato)
                    {
                        $datos = $unCampo;
                        $primerDato = false;
                    }
                    else
                    {
                        $datos = $datos . "," . $unCampo;
                        $primerDato = false;
                    }
                }
                $datos =  $datos . PHP_EOL;
                fwrite($archivo, $datos);
            }
        } catch (\Throwable) {
            echo "Error";
        }finally{
            fclose($archivo);
        }
    }*/

}


?>