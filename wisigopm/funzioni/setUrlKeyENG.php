<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id',array('gt' => 26360));


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = Mage::getModel('catalog/product')->setStoreId(2)->load($product->getId());

    $category=getLastCategory($product2);
    $nome_sottocategoria=$category->getName();
    $nome_brand=$product2->getAttributeText("ca_brand");
    $sku=$product2->getSku();

    if ($product2->getTypeId()=="configurable"){
        $nome = ucfirst(strtolower($nome_sottocategoria . " " . $nome_brand));
        $url_key = $nome_sottocategoria . "-" . $nome_brand . "-" . $sku;
    }
    else if ($product2->getTypeId()=="simple"){
        $misura=$product2->getAttributeText("ca_misura");
        $nome = ucfirst(strtolower($nome_sottocategoria . " " . $nome_brand . " " . $misura));
        $url_key = $nome_sottocategoria . "-" . $nome_brand . "-" . $sku;
    }
    $product2->setUrlKey($url_key);
    $product2->setName($nome);
    $product2->save();


}
Mage::log("FINE");

function getLastCategory($product){
    $categoryModel = Mage::getModel( 'catalog/category' )->setStoreId(2);

//Get Array of Category Id's with Last as First (Reversed)
    $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
    $_category = $categoryModel->load($_categories[0]);

    return $_category;
}
?>