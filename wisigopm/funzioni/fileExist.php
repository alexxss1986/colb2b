<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$stringQuery = "select value,value_id from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='21239'";
$gallery = $readConnection->fetchAll($stringQuery);


foreach ($gallery as $row){
    $valore=$row["value"];
    $value_id=$row["value_id"];
    $filename="/home/coltortiboutique/public_html/media/catalog/product".$valore;
    if (file_exists($filename)) {

    } else {
        $query = "delete from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where value_id='".$value_id."'";
        $writeConnection->query($query);
    }
}