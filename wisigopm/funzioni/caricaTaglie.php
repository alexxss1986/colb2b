<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');


$attribute_model = Mage::getModel('eav/entity_attribute');
$attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

$attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
$attribute = $attribute_model->load($attribute_code);

$attribute_options_model->setAttribute($attribute);
$options = $attribute_options_model->getAllOptions(false);


foreach ($options as $option) {
    $id = $option['value'];
    $nome = $option['label'];

    $id_scalare=0;

    $query = 'insert into ' . $resource->getTableName("scalariusa_taglie") . ' (id_scalare,taglia,id_taglia) values("' . $id_scalare . '","' . $nome . '","' . $id . '")';
    $writeConnection->query($query);
}