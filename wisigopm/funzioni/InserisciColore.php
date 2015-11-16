<?php

require_once 'app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');


$attribute_model = Mage::getModel('eav/entity_attribute');
$attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

$attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
$attribute = $attribute_model->load($attribute_code);

$attribute_options_model->setAttribute($attribute);
$options = $attribute_options_model->getAllOptions(false);

foreach ($options as $option) {
    $colore = $option['value'];

    $query = "insert into " . $resource->getTableName('colore') . " (id_colore) values('" . $colore . "')";
    $writeConnection->query($query);

}