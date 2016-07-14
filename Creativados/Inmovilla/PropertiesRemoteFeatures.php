<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 16:55
 */

namespace Creativados\Inmovilla;


class PropertiesRemoteFeatures extends PropertyCall
{
    public function getTypes()
    {
        $this->getList("tipos", "cod_tipo", "tipo");
        return $this;
    }

    public function getStates()
    {
        $this->getList("tipos_conservacion", "idconservacion", "conserv");
        return $this;
    }
}