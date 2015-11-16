<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');


// recupero tutti i prodotti configurabili
$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id', 'configurable')
    ->addAttributeToFilter('entity_id', array('eq' => 66478));

$count_prodotti = 0;
$l=0;
foreach ($collection as $product) {
    $prodotto=Mage::getModel("catalog/product")->load($product->getId());
    $ids = $prodotto->getTypeInstance()->getUsedProductIds();
    $taglia=array();
    sort($ids);
    foreach ( $ids as $id ) {
        $simpleProduct=Mage::getModel("catalog/product")->load($id);
        if ($simpleProduct->getStockItem()->getIsInStock()==1){
            $taglia[$l]=$simpleProduct->getAttributeText("ca_misura");
            $l=$l+1;
        }
    }


    $stringaTaglia=implode(" | ",$taglia);

    $stringQuery = "select * from " . $resource->getTableName('wsca_taglia_prodotti') . " where id='" . $product->getId() . "'";
    $tagliaDB = $readConnection->fetchAll($stringQuery);
    if (count($tagliaDB)>0){
        $query = "update " . $resource->getTableName('wsca_taglia_prodotti') . " set taglia='" . $stringaTaglia . "' where id='" . $product->getId() . "'";
        $writeConnection->query($query);
    }
    else {
        $query = "insert into " . $resource->getTableName('wsca_taglia_prodotti') . " (taglia,id) values ('".$stringaTaglia."','".$product->getId()."')";
        $writeConnection->query($query);
    }

}

