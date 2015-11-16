<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


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
                ->addAttributeToSelect('*') // add all attributes - optional
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
                    ->addAttributeToSelect('*') // add all attributes - optional
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
                        ->addAttributeToSelect('*') // add all attributes - optional
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


        Mage::log("FINITO DESIGNER",null,$logFileName);
