<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename = "carryoverSI";
$logFileName = $filename . '.log';



$filename2 = "carryoverErrore";
$logFileName2 = $filename2 . '.log';



$file = fopen("carryover/valentino.csv","r");
$i=0;
while(! feof($file))
{
    $arrayCSV[$i]=fgetcsv($file);
    $i=$i+1;

}

fclose($file);

$id_brand=1095;

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$query = "delete from " . $resource->getTableName('prodotti_carryover') . " where id_brand='".$id_brand."'";
$writeConnection->query($query);

Mage::log("INIZIO",null,$logFileName);

for ($l = 0; $l < count($arrayCSV); $l++) {
    $barcode = $arrayCSV[$l][0];
    $variante = $arrayCSV[$l][1];
    $sku=$barcode."-".$variante;


    $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

    if ($productConfigurable) {
        Mage::log($sku,null,$logFileName);
        $productConfigurable->setCaCarryover(2502);
        $productConfigurable->getResource()->saveAttribute($productConfigurable, 'ca_carryover');

        $ids = $productConfigurable->getTypeInstance()->getUsedProductIds();
        // recupero tutti i prodotti semplici
        foreach ($ids as $id) {
            $productSimple = Mage::getModel('catalog/product')->load($id);
            $productSimple->setCaCarryover(2502);
            $productSimple->getResource()->saveAttribute($productSimple, 'ca_carryover');
        }

    }
    else {
        Mage::log($sku,null,$logFileName2);
    }


    $query = "insert into " . $resource->getTableName('prodotti_carryover') . " (id_prodotto,id_brand) values('" . $sku . "','" . $id_brand . "')";
    $writeConnection->query($query);

}

Mage::log("FINE",null,$logFileName);
?>