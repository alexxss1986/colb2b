<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id',array('gteq' => 26188));


foreach ($collection as $product) {
    Mage::log($product->getId());
    $product2 = Mage::getModel('catalog/product')->setStoreId(2)->load($product->getId());
    $nome_brand=$product2->getAttributeText("ca_brand");
    $id_colore=$product2->getData("ca_colore");
    $id_composizione=$product2->getData("ca_000003");
    $id_stagione=$product2->getData("ca_stagione");

    $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
    $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_colore");
    $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
        ->setAttributeFilter($attributeModel->getId())
        ->setStoreFilter(2)
        ->load();

    foreach ($_collection->toOptionArray() as $option) {
        if ($option['value'] == $id_colore) {
            $nome_colore = $option['label'];
            break;
        }
    }

    if ($nome_colore=="Mixed colours"){
        $nome_colore=$product2->getData("ca_codice_colore_fornitore");
    }


    $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
    $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_stagione");
    $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
        ->setAttributeFilter($attributeModel->getId())
        ->setStoreFilter(2)
        ->load();

    foreach ($_collection->toOptionArray() as $option) {
        if ($option['value'] == $id_stagione) {
            $nome_stagione = $option['label'];
            break;
        }
    }



    $category=getLastCategory($product2);
    $nome_sottocategoria=$category->getName();

    $parent = $category->getParentId();
    while ($parent != "2") {
        $id_categoria = $parent;
        $category = Mage::getModel('catalog/category')->setStoreId(2)->load($parent);
        $parent = $category->getParentId();
    }

    $category = Mage::getModel('catalog/category')->setStoreId(2)->load($id_categoria);
    $nome_categoria = $category->getName();

    $numero = rand(0, 2);

    $stringa = "Shop";


    $title=ucwords(strtolower($nome_categoria))." ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) ." " . ucwords(strtolower($nome_colore));
    $description = $stringa . " online on coltortiboutique.com: " . ucwords(strtolower($nome_categoria))." ".ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria) . " " . strtolower($nome_colore) . " of " . strtolower($nome_stagione) . ". Guaranteed express delivery and returns";

    $keyword1 = $title;
    $keyword2 = ucwords(strtolower($nome_categoria))." ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria));
    $keyword3 = "Shop online ".ucwords(strtolower($nome_categoria))." ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria));

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
    $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

    return $_category;
}
?>