<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename = "esporta2";
$logFileName = $filename . '.log';

$i=0;
$data[$i][0]="Id";
$data[$i][1]="Nome";
$data[$i][2]="Descrizione";

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id', array(
        'from' => 1,
        'to' => 27502
    ));

$i=$i+1;

foreach ($collection as $product) {
    Mage::log($product->getId(),null,$logFileName);
    $prodotto = Mage::getModel('catalog/product')->load($product->getId());
    $data[$i][0]=$prodotto->getSku();
    $data[$i][1]=$prodotto->getData("ca_name");
    $data[$i][2]=strip_tags($prodotto->getDescription());

    $i=$i+1;
}


$fp = fopen("../../var/export/prodotti.csv", 'w');
foreach ($data as $dati) {
    fputcsv($fp, $dati);
}
fclose($fp);

Mage::log("FINE",null,$logFileName);

?>