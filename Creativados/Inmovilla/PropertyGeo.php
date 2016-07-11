<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 11/07/2016
 * Time: 13:38
 */

namespace Creativados\Inmovilla;


class PropertyGeo extends PropertyCall
{
    public function getProvinces()
    {
        return $this->gesList("provincias", "codprov", "provincia");
    }

    public function getProvincesAviables()
    {
        return $this->gesList("provinciasofertas", "codprov", "provincia");
    }

    public function getCities($codProv)
    {
        $temp = [];
        foreach ($this->getData("ciudades") as $value) {
            if ($value["codprov"] == $codProv) {
                $temp[$value["cod_ciu"]] = trim($value["city"]);
            }
        }
        return $temp;
    }

    public function getZones($codciu)
    {
        return $this->gesList("zonas", "cod_zona", "zone", "ciudad.codciu=" . $codciu);
    }
}