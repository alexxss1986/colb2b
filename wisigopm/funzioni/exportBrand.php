<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$i=0;
$data[$i][0]="Brand";

$attribute_model = Mage::getModel('eav/entity_attribute');
$attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

$attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
$attribute = $attribute_model->load($attribute_code);

$attribute_options_model->setAttribute($attribute);
$options = $attribute_options_model->getAllOptions(false);

$i=0;
foreach($options as $option) {
    $data[$i][0]=$option['value'];
    $data[$i][1]=$option['label'];
    $i=$i+1;
}



$fp = fopen("../../var/export/brand.csv", 'w');
foreach ($data as $dati) {
    fputcsv($fp, $dati);
}
fclose($fp);


?>