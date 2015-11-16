<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        // creo array categorie in questo ordine:
        // uomo -> abbigliamento, accessori, borse,calzature
        // donna -> abbigliamento, accessori, borse,calzature

        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');

        $arrayCategorie = array("4", "54", "57", "18", "14", "33", "47", "82");
        for ($i = 0; $i < count($arrayCategorie); $i++) {

            Mage::log($arrayCategorie[$i]);

            $categoria=Mage::getModel("catalog/category")->load($arrayCategorie[$i]);
            $id_categoria=$categoria->getId();
            $sesso=$categoria->getParentId();
            $nome_categoria=$categoria->getName();
            $url_key=$categoria->getUrlKey();
            $parent="";

            $categoriaEng=Mage::getModel("catalog/category")->setStoreId(2)->load($arrayCategorie[$i]);
            $nome_categoriaEng=$categoriaEng->getName();
            $url_keyEng=$categoriaEng->getUrlKey();

            $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                ->getProductCollection()
                ->addAttributeToSelect('*') // add all attributes - optional
                ->addAttributeToFilter('status', 1) // enabled
                ->addAttributeToFilter('visibility', 4);//visibility in catalog,search
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);


            if (count($collection) > 0) {

                $query = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id) values("' . $id_categoria . '","' . $nome_categoria . '","' . $url_key . '","' . $sesso . '","' . $parent . '","' . $i . '","1")';
                $writeConnection->query($query);

                $queryENG = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id) values("' . $id_categoria . '","' . $nome_categoriaEng . '","' . $url_keyEng . '","' . $sesso . '","' . $parent . '","' . $i . '","2")';
                $writeConnection->query($queryENG);

                $subcats = $categoria->getChildren();


                foreach(explode(',',$subcats) as $subCatid)
                {

                    $categoria = Mage::getModel('catalog/category')->load($subCatid);
                    $id_categoria=$categoria->getId();
                    $nome_categoria=$categoria->getName();
                    $url_key=$categoria->getUrlKey();
                    $parent=$categoria->getParentId();


                    $categoriaEng=Mage::getModel("catalog/category")->setStoreId(2)->load($subCatid);
                    $nome_categoriaEng=$categoriaEng->getName();
                    $url_keyEng=$categoriaEng->getUrlKey();

                    Mage::log($subCatid);

                    $collection = Mage::getModel('catalog/category')->load($subCatid)
                        ->getProductCollection()
                        ->addAttributeToSelect('*') // add all attributes - optional
                        ->addAttributeToFilter('status', 1) // enabled
                        ->addAttributeToFilter('visibility', 4);//visibility in catalog,search
                    Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);


                    if (count($collection) > 0) {
                        $query = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id) values("' . $id_categoria . '","' . $nome_categoria . '","' . $url_key . '","' . $sesso . '","' . $parent . '","' . $i . '","1")';
                        $writeConnection->query($query);

                        $queryENG = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id) values("' . $id_categoria . '","' . $nome_categoriaEng . '","' . $url_keyEng . '","' . $sesso . '","' . $parent . '","' . $i . '","2")';
                        $writeConnection->query($queryENG);
                    }

                }
            }

        }

Mage::log("FINE");