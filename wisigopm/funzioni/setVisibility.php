<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter("ca_stagione", 2346)
    ->addAttributeToFilter("type_id", "simple");

$filename="stagione";
$logFileName= $filename.'.log';


foreach ($collection as $product) {
    $id=$product->getId();
    $product2 = mage::getModel('catalog/product')->load($product->getId());
    $nome_brand=$product2->getAttributeText("ca_brand");

    $category=getLastCategory($product2);
    $parent = $category->getParentId();
    while ($parent != "2") {
        $id_categoria = $parent;
        $category = Mage::getModel('catalog/category')->load($parent);
        $parent = $category->getParentId();
    }

    $category = Mage::getModel('catalog/category')->load($id_categoria);
    $nome_categoria = $category->getName();

    Mage::log($product->getId(),null,$logFileName);




    if (((strtolower($nome_brand) == "adidas raf simons" && strtolower($nome_categoria) == "uomo") ||
            strtolower($nome_brand) == "alexander wang" ||
            strtolower($nome_brand) == "bally" ||
            (strtolower($nome_brand) == "barba napoli" && strtolower($nome_categoria) == "uomo") ||
            strtolower($nome_brand) == "burberry" ||
            (strtolower($nome_brand) == "charlotte olympia" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "chiara ferragni" && strtolower($nome_categoria) == "donna") ||
            strtolower($nome_brand) == "dolce & gabbana" ||
            strtolower($nome_brand) == "drome" ||
            strtolower($nome_brand) == "dsquared2" ||
            (strtolower($nome_brand) == "edward achour" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "emanuela caruso" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "ermanno scervino" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "faliero sarti" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "fausto puglisi" && strtolower($nome_categoria) == "donna") ||
            strtolower($nome_brand) == "fendi" ||
            (strtolower($nome_brand) == "gianluca capannolo" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "gianvito rossi" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "giorgio armani" && strtolower($nome_categoria) == "uomo") ||
            (strtolower($nome_brand) == "emporio armani" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "giorgio brato" && strtolower($nome_categoria) == "uomo") ||
            strtolower($nome_brand) == "giuseppe zanotti" ||
            strtolower($nome_brand) == "golden goose" ||
            strtolower($nome_brand) == "haider ackermann" ||
            (strtolower($nome_brand) == "jil sander" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "jimmy choo" && strtolower($nome_categoria) == "donna") ||
            strtolower($nome_brand) == "kenzo" ||
            strtolower($nome_brand) == "lanvin" ||
            strtolower($nome_brand) == "marco bologna" ||
            (strtolower($nome_brand) == "marcelo burlon" && strtolower($nome_categoria) == "uomo") ||
            (strtolower($nome_brand) == "michael kors" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "missoni" && strtolower($nome_categoria) == "donna") ||
            strtolower($nome_brand) == "moncler" ||
            strtolower($nome_brand) == "moncler gamme rouge/bleu" ||
            strtolower($nome_brand) == "mr & mrs italy" ||
            strtolower($nome_brand) == "msgm" ||
            (strtolower($nome_brand) == "peter pilotto" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "pierre louis mascia" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "pollini" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "proenza schouler" && strtolower($nome_categoria) == "donna") ||
            strtolower($nome_brand) == "rick owens" ||
            strtolower($nome_brand) == "sacai" ||
            strtolower($nome_brand) == "saint laurent" ||
            strtolower($nome_brand) == "salvatore ferragamo" ||
            (strtolower($nome_brand) == "stella mccartney" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "stuart weitzman" && strtolower($nome_categoria) == "donna") ||
            (strtolower($nome_brand) == "tagliatore" && strtolower($nome_categoria) == "uomo") ||
            (strtolower($nome_brand) == "tonello" && strtolower($nome_categoria) == "uomo") ||
            strtolower($nome_brand) == "valentino") &&
        ($id != "151405ABS000006-F0V1Z" &&
            $id != "151405ABS000006-F0KUR" &&
            $id != "151405ABS000006-F0V20" &&
            $id != "151405ABS000006-F0V21" &&
            $id != "151405ABS000007-F0V2N" &&
            $id != "151405ABS000007-F0KUR" &&
            $id != "151405ABS000007-F0V2M" &&
            $id != "151405ABS000007-F0L92" &&
            $id != "151405ABS000008-F0V1W" &&
            $id != "151405ABS000008-F0V1X" &&
            $id != "151405ABS000009-F0V8G" &&
            $id != "151405ABS000054-F0DVU" &&
            $id != "151405ABS000054-F0Z29" &&
            $id != "151405ABS000055-F0Y7W" &&
            $id != "151405ABS000056-F018C" &&
            $id != "151405ABS000056-F018B" &&
            $id != "151405ABS000057-F0GN2" &&
            $id != "151405ABS000057-F0F89" &&
            $id != "151405ABS000057-F0U52" &&
            $id != "151405ABS000057-F0W6Q" &&
            $id != "151405ABS000057-F0L17" &&
            $id != "151405ABS000057-F0V1A" &&
            $id != "151405ABS000057-F0A22" &&
            $id != "151405ABS000057-F0KUR" &&
            $id != "151405ABS000057-F0M8A")

    ) {
        $product2->setVisibility(1);
        $product2->save();
    }




}

Mage::log("FINE",null,$logFileName);


function getLastCategory($product){
    $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
    $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
    $_category = $categoryModel->load($_categories[0]);

    return $_category;
}
?>