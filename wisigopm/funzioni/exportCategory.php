<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$i=0;
$categories = Mage::getModel('catalog/category')
    ->getCollection();

$i=0;
foreach($categories as $categoria) {
    $cat=Mage::getModel('catalog/category')->load($categoria->getId());
    $data[$i][0]=$cat->getName();
    $i=$i+1;
}



$fp = fopen("../../var/export/categorie.csv", 'w');
foreach ($data as $dati) {
    fputcsv($fp, $dati);
}
fclose($fp);


?>