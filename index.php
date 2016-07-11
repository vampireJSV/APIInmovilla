<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 13:03
 */

require __DIR__ . '/vendor/autoload.php';

use Creativados\Inmovilla;

$server = new Inmovilla\Server(3783, "09823_jlkHG_Mar", 0);

$features = new Inmovilla\PropertyFeatures($server);
//var_dump($features->getTypes());
//var_dump($features->getStates());

$features = new Inmovilla\PropertyGeo($server);
//var_dump($features->getProvinces());
var_dump($features->getProvincesAviables());
var_dump($features->getCities(45));
var_dump($features->getZones(709399));


//$server->add_stack_call('tipos_conservacion', 1, 100, "", "");
//$server->add_stack_call('tipos', 1, 100, "", "");
//$server->add_stack_call('ciudades', 1, 100);
//$server->add_stack_call('zonas', 1, 100, "key_loca=32899");
//$server->add_stack_call('paginacion', 1, 100, "keyacci=1", "fecha desc");
//$server->add_stack_call('provincias',1, 100);
$server->add_stack_call('zonas', 1, 100);


var_dump($server->getData());
