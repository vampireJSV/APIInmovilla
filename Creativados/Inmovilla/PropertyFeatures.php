<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 16:55
 */

namespace Creativados\Inmovilla;


class PropertyFeatures extends PropertyCall
{

    public function getTypes()
    {
        return $this->gesList("tipos", "cod_tipo", "tipo");
    }

    public function getStates()
    {
        return $this->gesList("tipos_conservacion", "idconservacion", "conserv");
    }
}