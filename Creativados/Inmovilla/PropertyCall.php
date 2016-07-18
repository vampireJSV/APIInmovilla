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
    const NUM_ELEMENTS = 999;

    /**
     * Type constructor.
     * @param $connexion
     */
    public function __construct(Server $connexion)
    {
        $this->connexion = $connexion;
    }

    public function getList($function, $keyField, $valueField, $where = '')
    {
        $temp = [];
        foreach ($this->getData($function, $where) as $value) {
            $temp[$value[$keyField]] = trim($value[$valueField]);
        }
        asort($temp);
        $this->var = $temp;
    }

    public function getData($function, $where = '', $offset = 1, $num = self::NUM_ELEMENTS)
    {
        $meta_info = ['posicion' => $offset, 'total' => 0, 'elementos' => 0];
        $temp = [];
        do {
            $output = $this->connexion->process($function, $meta_info['posicion'] + $meta_info['elementos'],
                $num, $where);
            if (isset($output[$function])) {
                $output = $output[$function];
                $meta_info = array_shift($output);
                $temp = array_merge($temp, $output);
            }
        } while (($meta_info['posicion'] + $meta_info['elementos'] - 1) < $meta_info['total']);
        return $temp;
    }
}