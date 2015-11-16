<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$arrayCategorie = array("16", "17", "35", "24", "36", "39", "60", "65", "66", "79", "86", "89", "42", "45", "98", "100");
for ($i = 0; $i < count($arrayCategorie); $i++) {
    $categoria = Mage::getModel("catalog/category")->load($arrayCategorie[$i]);
    $collection = Mage::getResourceModel('catalog/product_collection')->addCategoryFilter($categoria);
    $flag=false;
    foreach ($collection as $product){
        $prodotto=Mage::getModel("catalog/product")->load($product->getId());
        $arrayCat=$prodotto->getCategoryIds();
        for ($l=0; $l<count($arrayCat); $l++){
            if ($arrayCat[$l]=="14"){
                $flag=true;
                break;
            }
        }

        if ($flag==false){
            Mage::log($prodotto->getSku());
        }
    }
}
