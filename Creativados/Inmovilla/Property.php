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

    public function enviroment()
    {
        $enviroment = [];
        for ($i = 0; $i < 17; $i++) {
            $enviroment[$i] = (($this->x_entorno & pow(2, $i)) == pow(2, $i));
        }
        return $enviroment;
    }

    public function find($id)
    {
        $this->setCod($id);
        return $this->get();
    }

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
}