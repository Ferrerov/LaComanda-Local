<?php

class GestorCsv
{
    public static function GuardarCsv($datos, $ruta)
    {
        try {
            fputcsv($ruta, array_keys((array)$datos[0]));

            // Datos de los datos
            foreach ($datos as $pedido) {
                fputcsv($ruta, (array)$pedido);
            }

            rewind($ruta);
            $archivo = stream_get_contents($ruta);
            fclose($ruta);

        } catch (\Throwable $e) {
            echo $e->getMessage();
        }finally{
            return $archivo;
        }
    }

    public static function LeerCsv($ruta){
        $archivo = fopen($ruta, "r");
        $array = array();
        try {

            while (!feof($archivo)) {
                $unaLinea = fgets($archivo);
                
                if (!empty($unaLinea)) {
                    $unaLinea = str_replace(PHP_EOL, "", $unaLinea);
                    $arrayLinea = explode(",", $unaLinea);
                    array_push($array, $arrayLinea);
                }
            }
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }finally{
            fclose($archivo);
            return $array;
        }
    }

}


?>