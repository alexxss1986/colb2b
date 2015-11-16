<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename = "carryover";
$logFileName = $filename . '.log';

$id_brand="1008";

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$file = fopen("carryover/christian-dior.csv","r");
$i=0;
while(! feof($file))
{
    $arrayCSV[$i]=fgetcsv($file);
    $i=$i+1;

}

fclose($file);

Mage::log("INIZIO",null,$logFileName);

for ($l = 0; $l < count($arrayCSV); $l++) {
    $barcode = $arrayCSV[$l][0];
    $variante = $arrayCSV[$l][1];
    $sku=$barcode."-".$variante;

    $stringQuery = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku . "'";
    $idBrand = $readConnection->fetchAll($stringQuery);

    if ($idBrand==null) {

        $query = "insert into " . $resource->getTableName('prodotti_carryover') . " (id_prodotto,id_brand) values('" . $sku . "','" . $id_brand . "')";
        $writeConnection->query($query);

    }


}

Mage::log("FINE",null,$logFileName);
?>