<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 13/07/2016
 * Time: 16:55
 */

namespace Creativados\Inmovilla;


class Property
{
    public $x_entorno = 0;

    /**
     * Property constructor.
     * @param $values
     */
    public function __construct($values)
    {
        foreach ($values as $key => $value) {
            $this->{$key} = $value;
        }
    }

    private function pretty()
    {

    }

    public function enviroment()
    {
        $enviroment = [];
        for ($i = 0; $i < 17; $i++) {
            $enviroment[$i] = (($this->x_entorno & pow(2, $i)) == pow(2, $i));
        }
        return $enviroment;
    }
}