<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 13/07/2016
 * Time: 16:55
 */

namespace Creativados\Inmovilla;


class Property extends PropertyCall
{
    const ENVIRONMENT = [
        0 => "Árboles",
        1 => "Hospitales",
        2 => "Tren",
        3 => "Metro",
        4 => "Golf",
        5 => "Montaña",
        6 => "Rural",
        7 => "Costa",
        8 => "Vallado",
        9 => "Autobuses",
        10 => "Centros Comerciales",
        11 => "Tranvía",
        12 => "Zonas Infantiles",
        13 => "Colegios",
        14 => "Céntrico",
        15 => "Centro de Salud",
        16 => "Zona de Paso"
    ];
    const BEAUTIFIERS = ["operation" => "keyacci"];
    const BEAUTIFIERS_VALUES = [
        'operation' => [
            1 => "Vender",
            2 => "Alquilar",
            3 => "Traspasar",
            4 => "Vender o Alquilar",
            5 => "Vender o Traspasar",
            6 => "Traspasar o Alquilar",
            7 => "Traspasar, Vender o Alquilar",
            8 => "Fuera de Mercado",
            9 => "Temporada Alquiler",
        ]
    ];
    public $x_entorno = 0;

    /**
     * Property constructor.
     * @param Server $connexion
     * @param $values
     */
    public function __construct(Server $connexion, $values)
    {
        parent::__construct($connexion);
        $this->set_values($values);
    }

    /**
     * @param $id
     */
    public function setCod($id)
    {
        $this->reset();
        $this->cod_ofer = $id;
    }

    private function reset()
    {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }

    /**
     * @return array
     */
    public function environment()
    {
        $environment = [];
        for ($i = 0; $i < 17; $i++) {
            $environment[$i] = (($this->x_entorno & pow(2, $i)) == pow(2, $i));
        }
        return $environment;
    }

    /**
     * @return array
     */
    public function environmentText()
    {
        $temp = [];
        foreach ($this->environment() as $key => $value) {
            if ($value) {
                $temp[$key] = self::ENVIRONMENT[$key];
            }
        }
        return $temp;
    }

    /**
     * @param $id
     * @return bool
     */
    public function find($id)
    {
        $this->setCod($id);
        return $this->get();
    }

    /**
     * @return bool
     */
    public function get()
    {
        $output = [];
        $return = false;
        if (isset($this->cod_ofer) && $this->cod_ofer != 0) {
            $data = $this->connexion->process("ficha", 1, 1, "cod_ofer=" . $this->cod_ofer);
            if (isset($data['ficha'])) {
                $output = $data['ficha'][1];
                if (count($data['fotos'])) {
                    $output['fotos'] = $data['fotos'][$this->cod_ofer];
                } else {
                    $output['fotos'] = [];
                }
                if (count($data['descripciones'])) {
                    $output['descripciones'] = $data['descripciones'][$this->cod_ofer];
                } else {
                    $output['descripciones'] = [];
                }
                if (count($data['videos'])) {
                    $output['videos'] = $data['videos'][$this->cod_ofer];
                } else {
                    $output['videos'] = [];
                }
            }
            $return = true;
        }
        $this->set_values($output);
        return $return;
    }

    /**
     * @param $values
     */
    private function set_values($values)
    {
        foreach ($values as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        if (in_array($method, array_keys(self::BEAUTIFIERS))) {
            return $this->beautifier($method);
        }
    }

    /**
     * @param $operation
     * @return string
     */
    private function beautifier($operation)
    {
        return self::BEAUTIFIERS_VALUES[$operation][$this->{self::BEAUTIFIERS[$operation]}];
    }
}