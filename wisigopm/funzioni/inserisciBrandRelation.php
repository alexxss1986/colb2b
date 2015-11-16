<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');

$attribute_model = Mage::getModel('eav/entity_attribute');
$attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

$attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
$attribute = $attribute_model->load($attribute_code);

$attribute_options_model->setAttribute($attribute);
$options = $attribute_options_model->getAllOptions(false);

foreach($options as $option) {
    $option_id=$option['value'];
    $option_label=$option['label'];

    $option_label=htmlentities($option_label, ENT_QUOTES, 'utf-8');

    $query = "insert into " . $resource->getTableName('brand_relation') . " (id,nome) values('" . $option_id . "','" . $option_label . "')";
    $writeConnection->query($query);

}

?>