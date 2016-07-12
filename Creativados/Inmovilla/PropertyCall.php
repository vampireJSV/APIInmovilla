<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 11/07/2016
 * Time: 13:46
 */

namespace Creativados\Inmovilla;


abstract class PropertyCall
{
    protected $connexion;
    const NUM_ELEMENTS = 30;

    /**
     * Type constructor.
     * @param $connexion
     */
    public function __construct(Server $connexion)
    {
        $this->connexion = $connexion;
    }

    public function gesList($function, $keyField, $valueField, $where = '')
    {
        $temp = [];
        foreach ($this->getData($function) as $value) {
            $temp[$value[$keyField]] = trim($value[$valueField]);
        }
        asort($temp);
        return $temp;
    }

    public function getData($function, $where = '')
    {
        $meta_info = ['posicion' => 1, 'total' => 0, 'elementos' => 0];
        $temp = [];
        do {
            $output = $this->connexion->process($function, $meta_info['posicion'] + $meta_info['elementos'], self::NUM_ELEMENTS, $where);
            if (isset($output[$function])) {
                $output = $output[$function];
                $meta_info = array_shift($output);
                $temp = array_merge($temp, $output);
            }
        } while (($meta_info['posicion'] + $meta_info['elementos'] - 1) < $meta_info['total']);
        return $temp;
    }
}