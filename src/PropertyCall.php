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

    public function getMeta(
        $function,
        $where = '',
        $offset = 1,
        $num = self::NUM_ELEMENTS,
        $sort = 'ref',
        $sortDirection = 'asc'
    ) {
        list($meta_info, $temp, $max, $num) = $this->initCall($offset, $num);
        $output = $this->connexion->process($function, $meta_info['posicion'] + $meta_info['elementos'],
            $num, $where, $sort . ' ' . $sortDirection);
        if (isset($output[$function])) {
            $output = $output[$function];
            $meta_info = array_shift($output);
        } else {
            $meta_info = [];
        }
        return $meta_info;
    }

    public function getData(
        $function,
        $where = '',
        $offset = 1,
        $num = self::NUM_ELEMENTS,
        $sort = 'ref',
        $sortDirection = 'asc'
    ) {
        list($meta_info, $temp, $max, $num) = $this->initCall($offset, $num);
        do {
            $output = $this->connexion->process($function, $meta_info['posicion'] + $meta_info['elementos'],
                $num, $where, $sort . ' ' . $sortDirection);
            if (isset($output[$function])) {
                $output = $output[$function];
                $meta_info = array_shift($output);
                $temp = array_merge($temp, $output);
                if ($meta_info['total'] < $max || $max == 0) {
                    $max = $meta_info['total'];
                }
            } else {
                $max = 0;
            }
        } while (($meta_info['posicion'] + $meta_info['elementos'] - 1) < $max);
        return $temp;
    }

    /**
     * @param $offset
     * @param $num
     * @return array
     */
    private function initCall($offset, $num)
    {
        $meta_info = ['posicion' => $offset, 'total' => 0, 'elementos' => 0];
        $temp = [];
        $max = $num;
        if ($num == 0) {
            $num = self::NUM_ELEMENTS;
            return array($meta_info, $temp, $max, $num);
        }
        return array($meta_info, $temp, $max, $num);
    }
}