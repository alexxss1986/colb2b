<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');

$arrayCategorie = array("3", "13");
for ($i = 0; $i < count($arrayCategorie); $i++) {
    $categoria = Mage::getModel('catalog/category')->load($arrayCategorie[$i]);


    $attribute_model = Mage::getModel('eav/entity_attribute');
    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
    $attribute = $attribute_model->load($attribute_code);

    $attribute_options_model->setAttribute($attribute);
    $options = $attribute_options_model->getAllOptions(false);

    $categoriaEng=Mage::getModel("catalog/category")->setStoreId(2)->load($arrayCategorie[$i]);
    foreach ($options as $option) {



        $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
            ->getProductCollection()
            ->addAttributeToSelect('*') // add all attributes - optional
            ->addAttributeToFilter('status', 1) // enabled
            ->addAttributeToFilter('visibility', 4)
            ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

        if (count($collection) > 0) {

            $id=$option['value'];
            $nome=$option['label'];
            $prodotti=1;
            $visibile=1;



            $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoriaEng->getId() . '","' . $visibile . '")';
            $writeConnection->query($query);


            $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
            $writeConnection->query($queryEng);
        } else {

            $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                ->getProductCollection()
                ->addAttributeToSelect('*') // add all attributes - optional
                ->addAttributeToFilter('status', 1) // enabled
                ->addAttributeToFilter('visibility', 4)
                ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
            if (count($collection) > 0) {
                $id=$option['value'];
                $nome=$option['label'];
                $prodotti=0;
                $visibile=1;

                $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoriaEng->getId() . '","' . $visibile . '")';
                $writeConnection->query($query);

                $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                $writeConnection->query($queryEng);

            }
            else {
                $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                    ->getProductCollection()
                    ->addAttributeToSelect('*') // add all attributes - optional
                    ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                if (count($collection) > 0) {
                    $id=$option['value'];
                    $nome=$option['label'];
                    $prodotti=0;
                    $visibile=0;

                    $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoriaEng->getId() . '","' . $visibile . '")';
                    $writeConnection->query($query);

                    $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                    $writeConnection->query($queryEng);

                }
            }
        }





    }

}

Mage::log("FINE");