<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename = "cat1";
$logFileName = $filename . '.log';


$filename2 = "cat2";
$logFileName2 = $filename2 . '.log';

$products = Mage::getModel('catalog/category')->load(30)
    ->getProductCollection()
    ->addAttributeToSelect('*');


foreach ($products as $product){
    $flag=false;
    $prodotto=Mage::getModel("catalog/product")->load($product->getId());
    $arrayCat=$prodotto->getCategoryIds();
    for ($l=0; $l<count($arrayCat); $l++){
        if ($arrayCat[$l]=="14"){
            $flag=true;
            break;
        }
    }

    if ($flag==false){
        Mage::log($prodotto->getSku(),null,$logFileName);
    }
    else {
        Mage::log($prodotto->getSku(),null,$logFileName2);
    }
}