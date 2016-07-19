<?php
/**
 * Created by PhpStorm.
 * User: Jsv_S
 * Date: 08/07/2016
 * Time: 13:03
 */

require __DIR__ . '/vendor/autoload.php';

use \Creativados\Inmovilla;


$server = new Inmovilla\Server(3783, "09823_jlkHG_Mar", 0); //Marblau
//$server = new Inmovilla\Server(3812, "asdf0923kd__dslkjadsf91jKLKLJfds", 0); //Gijon
//$server->setLanguage(Inmovilla\Server::LANGUAGE_CATALAN);
$features = new Inmovilla\PropertiesRemoteFeatures($server);
$geo = new Inmovilla\PropertiesRemoteGeoValues($server);
$properties = new Inmovilla\Properties($server);
$property = new Inmovilla\Property($server, []);


//var_dump($features->getTypes()->current());
//var_dump($features->getTypes()->key());
//list($key, $value) = $features->getTypes()->get();
//var_dump($key, $value);
//
//var_dump($features->getStates()->current());
//var_dump($features->getStates()->key());

//$geo->getProvinces(Inmovilla\PropertyGeo::COUNTRY_SPAIN);
//$geo->getProvincesAviables();
//$geo->getCities(45);
//$geo->getCitiesAviables(45);
//$geo->getZones(709399);

//$properties->searchProperties();
//$properties->addWhere("key_loca", 709399);
//$properties->addWhere("total_hab", 2);
//$properties->searchProperties();
//$properties->addWhere("key_loca", 709399);
//$properties->addWhere("m_uties", 90);
//$properties->searchImportantProperties();
$property->find(3917958);
var_dump($property);
//var_dump($properties->getProperty(232323)->current());

//$server->add_stack_call('tipos_conservacion', 1, 100, "", "");
//$server->add_stack_call('tipos', 1, 100, "", "");
//$server->add_stack_call('ciudades', 1, 100);
//$server->add_stack_call('ciudades_todas', 1, 100, "ciudad.keyprov=45");
//$server->add_stack_call('zonas', 1, 100, "key_loca=32899");
//$server->add_stack_call('paginacion', 1, 100);
//$server->add_stack_call('paginacion', 1, 100, "m_uties=90", "fecha desc");
//$server->add_stack_call('destacados', 1, 100, "m_uties=90", "fecha desc");
//$server->add_stack_call('provincias',1, 100);
//$server->add_stack_call('zonas', 1, 100);
//$server->add_stack_call('zonas', 1, 100,"ciudad.cod_ciu=106199");

//foreach ($properties->searchProperties() as $property) {
//    $temp[] = "codigo=" . $property['cod_ofer'];
//}
//$server->add_stack_call('alquilerdisponibilidad', 1, 50, implode(' OR ', $temp));

$server->getData();

