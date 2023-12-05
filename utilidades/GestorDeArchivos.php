<?php

class GestorDeArchivos {
    public static function VerificarDirectorios($urlCarpetaImagen)
    {
        $carpetas = explode('/', $urlCarpetaImagen);
        $path='';

        foreach($carpetas as $carpeta)
        {
            $path .= $carpeta . '/';
            if(!file_exists($path))
            {
                mkdir($path, 0777);
            }
        }
    }
    public static function GuardarCsv($datos, $ruta)
    {
        try {
            fputcsv($ruta, array_keys((array)$datos[0]));
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

    public static function GuardarPDF($datos, $encabezados)
    {
        $pdf = new TCPDF('L', 'mm', 'A4');

        $pdf->SetHeaderData('', 0, 'Listado de Reservas', '');
        $pdf->setHeaderMargin(10);
        $pdf->setFooterMargin(15);
        $pdf->SetMargins(10, 20, 10);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);


        $anchoPagina = $pdf->GetPageWidth();
        $mitadAnchoPagina = $anchoPagina / 2;
        $anchoTabla = count($encabezados) * 30;
        $posicionX = $mitadAnchoPagina - ($anchoTabla / 2);

        $pdf->SetXY($posicionX, $pdf->GetY());
        foreach ($encabezados as $encabezado) {
            $pdf->Cell(30, 10, $encabezado, 1);
        }
        $pdf->Ln();

        foreach ($datos as $unDato) {
            $pdf->SetXY($posicionX, $pdf->GetY());
            foreach ($encabezados as $encabezado) {
                $pdf->Cell(30, 10, $unDato->$encabezado, 1);
            }
            $pdf->Ln();
        }

        return $pdf;
    }
}
