<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 16:55
 */

namespace Creativados\Inmovilla;


class PropertyFeatures
{
    private $conexion;

    /**
     * Type constructor.
     */
    public function __construct(Server $conexion)
    {
        $this->conexion = $conexion;
    }

    private function gesList($function, $keyfield, $valuefield)
    {
        $output = $this->conexion->process($function, 1, 9999)[$function];
        if ($output[0]['total'] > $output[0]['elementos']) {
            $output = $this->conexion->process($function, 1, $output[0]['total'])[$function];
        }
        array_shift($output);
        $temp = [];
        foreach ($output as $value) {
            $temp[$value[$keyfield]] = $value[$valuefield];
        }
        asort($temp);
        return $temp;
    }

    public function getTypes()
    {
        return $this->gesList("tipos", "cod_tipo", "tipo");
    }

    public function getStates()
    {
        return $this->gesList("tipos_conservacion", "idconservacion", "conserv");
    }
}