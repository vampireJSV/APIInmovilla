<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 11/07/2016
 * Time: 13:46
 */

namespace Creativados\Inmovilla;


abstract class PropertyCall implements \Iterator
{
    protected $connexion;
    const NUM_ELEMENTS = 999;
    protected $var = array();

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

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->var);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        return next($this->var);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->var);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $clave = key($this->var);
        $var = ($clave !== null && $clave !== false);
        return $var;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->var);
    }

    public function get()
    {
        return [$this->key(), $this->current()];
    }
}