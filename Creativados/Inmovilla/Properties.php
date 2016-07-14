<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 12/07/2016
 * Time: 16:12
 */

namespace Creativados\Inmovilla;


class Properties extends PropertyCall
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
        "ascensor" => [0, 1],
        "aire_con" => [0, 1],
        "piscina_com" => [0, 1],
        "piscina_prop" => [0, 1],
        "diafano" => [0, 1],
        "todoext" => [0, 1],
        "foto" => "",
        "calefacción" => [0, 1],
        "aire_con" => [0, 1],
        "trastero" => [0, 1],
        "key_tipo" => 1,
        "key_loca" => 1,
        "key_zona" => 1,
        "conservacion" => 1
    ];
    const ACTION_VENTA = 1;
    const ACTION_ALQUILER = 2;
    const ACTION_TRASPASO = 3;
    const OPERATION_EQUAL = "=";
    const OPERATION_DISTINC = "!=";
    const OPERATION_GREAT = ">";
    const OPERATION_LESS = "<";
    const OPERATION_GREAT_EQUAL = ">=";
    const OPERATION_lESS_EQUAL = "<=";
    const OPERATION_LIKE = " LIKE ";

    private $where = [];

    private function validateWhere($key, $value)
    {
        if (in_array($key, array_keys(self::WHERE_FIELDS))) {
            $filter = self::WHERE_FIELDS[$key];
            switch ($filter) {
                case 1:
                    return is_integer($value);
                    break;
                case "":
                    return is_string($value);
                    break;
                default:
                    return in_array($value, $filter);
                    break;
            }
        }
        return false;
    }

    public function addWhere($key, $value, $operacion = self::OPERATION_EQUAL)
    {
        if ($this->validateWhere($key, $value)) {

            $this->where[$operacion][$key] = $value;
        }
    }

    private function merge($operation, $key, $value)
    {
        $string = '';
        $filter = self::WHERE_FIELDS[$key];
        if (is_array($filter)) {
            $filter = array_shift($filter);
        }
        if (is_integer($filter)) {
            $string = $key . $operation . $value;
        } else {
            $string = $key . $operation . "''" . $value . "''";
        }
        return $string;
    }

    public function searchProperties($offset = 1, $num_elements = 50, $merge = 'AND')
    {
        return $this->callSearchProperties("paginacion", $offset, $num_elements, $merge, 50);
    }

    public function searchImportantProperties($offset = 1, $num_elements = 30, $merge = 'AND')
    {
        return $this->callSearchProperties("destacados", $offset, $num_elements, $merge, 30);
    }

    /**
     * @param $function
     * @param $offset
     * @param $num_elements
     * @param $merge
     * @param $max_elements
     * @return array
     */
    private function callSearchProperties($function, $offset, $num_elements, $merge, $max_elements)
    {
        if ($num_elements > $max_elements || $num_elements < 1) {
            $num_elements = $max_elements;
        }
        if ($offset < 1) {
            $offset = 1;
        }

        $where_string = [];
        foreach ($this->where as $operation => $par) {
            foreach ($par as $key => $value) {
                $where_string[] = $this->merge($operation, $key, $value);
            }

        }
        $this->where = [];
        $output = [];
        foreach ($this->getData($function, implode(" " . $merge . " ", $where_string), $offset,
            $num_elements) as $value) {
            $output[] = new Property($value);
        }

        $this->var = $output;
        return $this;
    }

    public function getProperty($id)
    {
        $output = [];
        $data = $this->connexion->process("ficha", 1, 1, "cod_ofer=" . $id);
        if (isset($data['ficha'])) {
            $output = $data['ficha'][1];
            if (count($data['fotos'])) {
                $output['fotos'] = $data['fotos'][$id];
            } else {
                $output['fotos'] = [];
            }
            if (count($data['descripciones'])) {
                $output['descripciones'] = $data['descripciones'][$id];
            } else {
                $output['descripciones'] = [];
            }
            if (count($data['videos'])) {
                $output['videos'] = $data['videos'][$id];
            } else {
                $output['videos'] = [];
            }
        }
        $this->var = [new Property($output)];
        return $this;
    }

}