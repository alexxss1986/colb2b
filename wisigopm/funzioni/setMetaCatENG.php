<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$categories = Mage::getModel('catalog/category')
    ->getCollection();


foreach ($categories as $category){
    Mage::log($category->getId());
    if ($category->getId()!="1" && $category->getId()!="2") {
        $categoria = Mage::getModel('catalog/category')->setStoreId(3)->load($category->getId());

        $parent = $categoria->getParentId();
        while ($parent != "2") {
            $sesso = $parent;
            $category = Mage::getModel('catalog/category')->setStoreId(3)->load($parent);
            $parent = $category->getParentId();
        }

        $categoriaParent = Mage::getModel('catalog/category')->setStoreId(3)->load($sesso);
        $sesso=$categoriaParent->getName();


        $titleITA = $categoria->getName()." ".$sesso." luxury fashion";

        $keywords1 = $categoria->getName();
        $keywords2 = $titleITA;
        $keywords3 = "Shop online ".$titleITA;
        $keywordsITA = $keywords1 . ", " . $keywords2 . ", " . $keywords3;

        $descriptionITA = "Shop online the best fashion collection of ".$categoria->getName()." for ".$sesso." designed by the greatest international luxury fashion brands. ColtortiBoutique guaranteed express delivery and returns";


        $general['meta_title'] = $titleITA;
        $general['meta_keywords'] = $keywordsITA;
        $general['meta_description'] = $descriptionITA;


        $categoria->addData($general);
        $categoria->save();
    }
}
Mage::log("FINE");