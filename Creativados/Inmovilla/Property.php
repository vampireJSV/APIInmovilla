<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 12/07/2016
 * Time: 16:12
 */

namespace Creativados\Inmovilla;


class Property extends PropertyCall
{
    const WHERE_FIELDS = [
        "cod_ofer" => 1,
        "ref" => "",
        "keyacci" => [1, 2, 3],
        "precioinmo" => 1,
        "outlet" => 1,
        "precioalq" => 1,
        "tipomensual" => ["MES", "QUI", "SEM", "DIA", "FIN"],
        "numfotos" => 1,
        "nbtipo" => "",
        "ciudad" => "",
        "zona" => "",
        "numagencia" => 1,
        "m_parcela" => 1,
        "m_uties" => 1,
        "m_cons" => 1,
        "m_terraza" => 1,
        "banyos" => 1,
        "aseos" => 1,
        "habdobles" => 1,
        "habitaciones" => 1,
        "total_hab" => 1,
        "distmar" => 1,
        "ascensor" => 0,
        "aire_con" => 0,
        "piscina_com" => 0,
        "piscina_prop" => 0,
        "diafano" => 0,
        "todoext" => 0,
        "foto" => "",
        "calefacción" => 0,
        "aire_con" => 0,
        "trastero" => 0,
        "key_tipo" => 1,
        "key_loca" => 1,
        "key_zona" => 1,
        "conservacion" => 1
    ];
    private $where = [];


    public function getProperties()
    {
        return $this->getData("paginacion");
    }

    public function addWhere($key, $value)
    {
        if (in_array($key, array_keys(self::WHERE_FIELDS))) {
            
            $this->where[$key] = $value;
        }
    }

    public function searchProperties($offset, $num_elements)
    {
        if ($num_elements > 50 || $num_elements < 1) {
            $num_elements = 50;
        }
        if ($offset < 1) {
            $offset = 1;
        }
    }
}