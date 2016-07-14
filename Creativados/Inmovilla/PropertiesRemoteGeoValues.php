<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 11/07/2016
 * Time: 13:38
 */

namespace Creativados\Inmovilla;


class PropertiesRemoteGeoValues extends PropertyCall
{
    const COUNTRY_SPAIN = 0;

    public function getProvinces($pais)
    {
        $this->getList("provincias", "codprov", "provincia", "and pais=" . $pais);
        return $this;
    }

    public function getProvincesAviables()
    {
        $this->getList("provinciasofertas", "codprov", "provincia");
        return $this;
    }

    public function getCities($codProv)
    {
        $this->getList("ciudades_todas", "cod_ciu", "city", "ciudad.keyprov=" . $codProv);
        return $this;
    }

    public function getCitiesAviables($codProv)
    {
        $this->getList("ciudades", "cod_ciu", "city", "ciudad.keyprov=" . $codProv);
        return $this;
    }

    public function getZones($codciu)
    {
        $this->getList("zonas", "cod_zona", "zone", "ciudad.cod_ciu=" . $codciu);
        return $this;
    }
}