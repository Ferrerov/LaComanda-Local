<?php

class Sector{
    public $idSector;
    public $nombre;

    public function __construct() {}
    public static function ConstruirSector($nombre)
    {
        $sector = new Sector();

        $sector->nombre = $nombre;

        return $sector;
    }
}

?>