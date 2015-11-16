<?php
class TreC_CaricaDesigner_Model_Observer
{
    public function load()
    {
        $filename = "catalogo";
        $logFileName = $filename . '.log';

        Mage::log("ENTRATO DESIGNER",null,$logFileName);
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $query = 'delete from ' . $resource->getTableName("designer_menu_brand");
        $writeConnection->query($query);

        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);


        foreach ($options as $option) {

            $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('status', 1) // enabled
                ->addAttributeToFilter('visibility', 4)
                ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

            if (count($collection) > 0) {

                $flag=false;
                foreach ($collection as $prodotti){
                    $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                    $countP = $readConnection->fetchOne($stringQuery);
                    if ($countP>0){
                        $flag=true;
                        break;
                    }
                }

                if ($flag) {
                    $id = $option['value'];
                    $nome = $option['label'];
                    $prodotti = 1;
                    $visibile = 0;


                    $query = 'insert into ' . $resource->getTableName("designer_menu_brand") . ' (id,nome,prodotti,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $visibile . '")';
                    $writeConnection->query($query);
                }
                else {
                    $id = $option['value'];
                    $nome = $option['label'];
                    $prodotti = 1;
                    $visibile = 1;


                    $query = 'insert into ' . $resource->getTableName("designer_menu_brand") . ' (id,nome,prodotti,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $visibile . '")';
                    $writeConnection->query($query);
                }



            } else {

                $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToFilter('status', 1) // enabled
                    ->addAttributeToFilter('visibility', 4)
                    ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                if (count($collection) > 0) {

                    $flag=false;
                    foreach ($collection as $prodotti){
                        $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                        $countP = $readConnection->fetchOne($stringQuery);
                        if ($countP>0){
                            $flag=true;
                            break;
                        }
                    }

                    if ($flag) {

                        $id = $option['value'];
                        $nome = $option['label'];
                        $prodotti = 0;
                        $visibile = 0;

                        $query = 'insert into ' . $resource->getTableName("designer_menu_brand") . ' (id,nome,prodotti,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $visibile . '")';
                        $writeConnection->query($query);
                    }
                    else {
                        $id = $option['value'];
                        $nome = $option['label'];
                        $prodotti = 0;
                        $visibile = 1;

                        $query = 'insert into ' . $resource->getTableName("designer_menu_brand") . ' (id,nome,prodotti,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $visibile . '")';
                        $writeConnection->query($query);
                    }

                }
                else {
                    $collection = Mage::getModel('catalog/product')->getCollection()
                        ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                    if (count($collection) > 0) {
                        $id=$option['value'];
                        $nome=$option['label'];
                        $prodotti=0;
                        $visibile=0;

                        $query = 'insert into ' . $resource->getTableName("designer_menu_brand") . ' (id,nome,prodotti,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $visibile . '")';
                        $writeConnection->query($query);

                    }
                }
            }


        }

        $query = 'delete from ' . $resource->getTableName("designer_menu");
        $writeConnection->query($query);

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
            $categoriaUsa=Mage::getModel("catalog/category")->setStoreId(3)->load($arrayCategorie[$i]);
            foreach ($options as $option) {



                $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                    ->getProductCollection()
                    ->addAttributeToFilter('status', 1) // enabled
                    ->addAttributeToFilter('visibility', 4)
                    ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

                if (count($collection) > 0) {

                    $flag=false;
                    foreach ($collection as $prodotti){
                        $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                        $countP = $readConnection->fetchOne($stringQuery);
                        if ($countP>0){
                            $flag=true;
                            break;
                        }
                    }

                    if ($flag) {
                        $id = $option['value'];
                        $nome = $option['label'];
                        $prodotti = 1;
                        $visibile = 0;


                        $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoria->getId() . '","' . $visibile . '")';
                        $writeConnection->query($query);


                        $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                        $writeConnection->query($queryEng);

                        $queryUsa = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaUsa->getUrlKey() . '","3","' . $categoriaUsa->getId() . '","' . $visibile . '")';
                        $writeConnection->query($queryUsa);
                    }
                    else {
                        $id = $option['value'];
                        $nome = $option['label'];
                        $prodotti = 1;
                        $visibile = 1;


                        $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoria->getId() . '","' . $visibile . '")';
                        $writeConnection->query($query);


                        $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                        $writeConnection->query($queryEng);

                        $queryUsa = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaUsa->getUrlKey() . '","3","' . $categoriaUsa->getId() . '","' . $visibile . '")';
                        $writeConnection->query($queryUsa);
                    }
                } else {

                    $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                        ->getProductCollection()
                        ->addAttributeToFilter('status', 1) // enabled
                        ->addAttributeToFilter('visibility', 4)
                        ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                    if (count($collection) > 0) {

                        $flag=false;
                        foreach ($collection as $prodotti){
                            $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                            $countP = $readConnection->fetchOne($stringQuery);
                            if ($countP>0){
                                $flag=true;
                                break;
                            }
                        }

                        if ($flag) {
                            $id = $option['value'];
                            $nome = $option['label'];
                            $prodotti = 0;
                            $visibile = 0;

                            $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoria->getId() . '","' . $visibile . '")';
                            $writeConnection->query($query);

                            $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryEng);

                            $queryUsa = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaUsa->getUrlKey() . '","3","' . $categoriaUsa->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryUsa);
                        }
                        else {
                            $id = $option['value'];
                            $nome = $option['label'];
                            $prodotti = 0;
                            $visibile = 1;

                            $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoria->getId() . '","' . $visibile . '")';
                            $writeConnection->query($query);

                            $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryEng);

                            $queryUsa = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaUsa->getUrlKey() . '","3","' . $categoriaUsa->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryUsa);
                        }

                    }
                    else {
                        $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                            ->getProductCollection()
                            ->addAttributeToFilter(array(array('attribute' => 'ca_brand', $option['value'])));
                        if (count($collection) > 0) {
                            $id=$option['value'];
                            $nome=$option['label'];
                            $prodotti=0;
                            $visibile=0;

                            $query = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoria->getUrlKey() . '","1","' . $categoria->getId() . '","' . $visibile . '")';
                            $writeConnection->query($query);

                            $queryEng = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaEng->getUrlKey() . '","2","' . $categoriaEng->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryEng);

                            $queryUsa = 'insert into ' . $resource->getTableName("designer_menu") . ' (id,nome,prodotti,sesso_url,store_id,sesso,visibile) values("' . $id . '","' . $nome . '","' . $prodotti . '","' . $categoriaUsa->getUrlKey() . '","3","' . $categoriaUsa->getId() . '","' . $visibile . '")';
                            $writeConnection->query($queryUsa);

                        }
                    }
                }





            }

        }

        Mage::log("FINITO DESIGNER",null,$logFileName);
    }
}