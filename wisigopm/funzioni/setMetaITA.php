<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id',array('gteq' => 26436));;


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = Mage::getModel('catalog/product')->load($product->getId());
    $nome_brand=$product2->getAttributeText("ca_brand");
    $colore=$product2->getData("ca_colore");

    $nome_colore=$product2->getAttributeText("ca_colore");

    if ($nome_colore=="Colori misti"){
        $nome_colore=$product2->getData("ca_codice_colore_fornitore");
    }


    $nome_stagione=$product2->getAttributeText("ca_stagione");

    $category=getLastCategory($product2);
    $nome_sottocategoria=$category->getName();

    $parent = $category->getParentId();
    while ($parent != "2") {
        $id_categoria = $parent;
        $category = Mage::getModel('catalog/category')->load($parent);
        $parent = $category->getParentId();
    }

    $category = Mage::getModel('catalog/category')->load($id_categoria);
    $nome_categoria = $category->getName();

        $numero = rand(0, 2);
        if ($numero == 0) {
            $stringa = "Acquista";
        }
        if ($numero == 1) {
            $stringa = "Compra";
        }
        if ($numero == 2) {
            $stringa = "Shop";
        }

        $title=ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da ".ucwords(strtolower($nome_categoria))." " . ucwords(strtolower($nome_colore));

        $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";

        $keyword1 = $title;
        $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da ".ucwords(strtolower($nome_categoria));
        $keyword3 = "Shop online ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da ".ucwords(strtolower($nome_categoria));

        $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;


    $product2->setMetaKeyword($keywords);
    $product2->setMetaDescription($description);
    $product2->setMetaTitle($title);
    $product2->save();


}
Mage::log("FINE");

function getLastCategory($product){
    $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
    $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
    $_category = $categoryModel->load($_categories[0]);

    return $_category;
}
?>