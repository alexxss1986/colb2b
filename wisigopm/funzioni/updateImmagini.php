<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$sku_configurabile="152197FPH000002-F068Z";
$product = Mage::getModel('catalog/product');
$product->load($product->getIdBySku($sku_configurabile));

$stringQuery = "select value_id,value from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='".$product->getId()."'";
$immagine = $readConnection->fetchAll($stringQuery);
foreach ($immagine as $image) {
    $path = $image["value"];
    $file = basename($path);

    $punto = strrpos($file, ".");
    $file_new = substr($file, 0, $punto);

    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
    $numero_img=substr($file_new,$posizione+1,strlen($file_new)-$posizione);


    $carattere=substr($numero_img,1,1);
    if ($carattere=="s" || $carattere=="t"){
        $numero_img=substr($numero_img,0,2);
    }
    else {
        $numero_img=substr($numero_img,0,1);
    }

    Mage::log($path);


        $stringQuery = "select value_id from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='".$product->getId()."' and value='".$path."'";
        $immagine = $readConnection->fetchOne($stringQuery);

        Mage::log($immagine);

        if ($numero_img == "3") {
            $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set disabled=0, position=1 where value_id="'.$immagine.'"';
            $writeConnection->query($query);
        } else if ($numero_img == "3s" || $numero_img == "3t") {
            $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set disabled=1, position=1 where value_id="'.$immagine.'"';
            $writeConnection->query($query);
        } else if ($numero_img == "4s") {
            $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set position=2,label="back" where value_id="'.$immagine.'"';
            $writeConnection->query($query);
        } else {
            $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set position="'.($numero_img - 2).'" where value_id="'.$immagine.'"';
            $writeConnection->query($query);
        }
    Mage::log("ok");
}