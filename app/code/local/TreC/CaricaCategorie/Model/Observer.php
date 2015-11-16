<?php
class TreC_CaricaCategorie_Model_Observer
{
    public function load()
    {
        $filename = "catalogo";
        $logFileName = $filename . '.log';

        Mage::log("ENTRATO CATEGORIE",null,$logFileName);
        // creo array categorie in questo ordine:
        // uomo -> abbigliamento, accessori, borse,calzature
        // donna -> abbigliamento, accessori, borse,calzature

        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $query = 'delete from ' . $resource->getTableName("categorie_menu");
        $writeConnection->query($query);

        $arrayCategorie = array("4", "54", "57", "18", "14", "33", "47", "82");
        for ($i = 0; $i < count($arrayCategorie); $i++) {

            $categoria=Mage::getModel("catalog/category")->load($arrayCategorie[$i]);
            $id_categoria=$categoria->getId();
            $sesso=$categoria->getParentId();
            $nome_categoria=$categoria->getName();
            $url_key=$categoria->getUrlKey();
            $parent="";

            $nome_categoria=str_replace(" uomo","",$nome_categoria);
            $nome_categoria=str_replace(" donna","",$nome_categoria);

            $categoriaEng=Mage::getModel("catalog/category")->setStoreId(2)->load($arrayCategorie[$i]);
            $nome_categoriaEng=$categoriaEng->getName();
            $url_keyEng=$categoriaEng->getUrlKey();

            $categoriaUsa=Mage::getModel("catalog/category")->setStoreId(3)->load($arrayCategorie[$i]);
            $nome_categoriaUsa=$categoriaUsa->getName();
            $url_keyUsa=$categoriaUsa->getUrlKey();

            $collection = Mage::getModel('catalog/category')->load($arrayCategorie[$i])
                ->getProductCollection()
                ->addAttributeToSelect('*') // add all attributes - optional
                ->addAttributeToFilter('status', 1) // enabled
                ->addAttributeToFilter('visibility', 4);//visibility in catalog,search
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);


            if (count($collection) > 0) {

                $flag=false;
                foreach ($collection as $prodotti){
                    $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                    $countP = $readConnection->fetchOne($stringQuery);
                    if ($countP==0){
                        $flag=true;
                        break;
                    }
                }

                if ($flag) {
                    $visibile=1;
                }
                else {
                    $visibile=0;
                }

                    $query = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoria . '","' . $url_key . '","' . $sesso . '","' . $parent . '","' . $i . '","1","' . $visibile . '")';
                    $writeConnection->query($query);

                    $queryENG = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoriaEng . '","' . $url_keyEng . '","' . $sesso . '","' . $parent . '","' . $i . '","2","' . $visibile . '")';
                    $writeConnection->query($queryENG);

                    $queryUSA = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoriaUsa . '","' . $url_keyUsa . '","' . $sesso . '","' . $parent . '","' . $i . '","3","' . $visibile . '")';
                    $writeConnection->query($queryUSA);

                    $subcats = $categoria->getChildren();


                    foreach (explode(',', $subcats) as $subCatid) {

                        $categoria = Mage::getModel('catalog/category')->load($subCatid);
                        $id_categoria = $categoria->getId();
                        $nome_categoria = $categoria->getName();
                        $url_key = $categoria->getUrlKey();
                        $parent = $categoria->getParentId();


                        $categoriaEng = Mage::getModel("catalog/category")->setStoreId(2)->load($subCatid);
                        $nome_categoriaEng = $categoriaEng->getName();
                        $url_keyEng = $categoriaEng->getUrlKey();

                        $categoriaUsa = Mage::getModel("catalog/category")->setStoreId(3)->load($subCatid);
                        $nome_categoriaUsa = $categoriaUsa->getName();
                        $url_keyUsa = $categoriaUsa->getUrlKey();


                        $collection = Mage::getModel('catalog/category')->load($subCatid)
                            ->getProductCollection()
                            ->addAttributeToSelect('*')// add all attributes - optional
                            ->addAttributeToFilter('status', 1)// enabled
                            ->addAttributeToFilter('visibility', 4);//visibility in catalog,search
                        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);


                        if (count($collection) > 0) {

                            $flag=false;
                            foreach ($collection as $prodotti){
                                $stringQuery = "select count(*) from " . $resource->getTableName('am_groupcat_product') . " where product_id='".$prodotti->getId()."' and rule_id='3'";
                                $countP = $readConnection->fetchOne($stringQuery);
                                if ($countP==0){
                                    $flag=true;
                                    break;
                                }
                            }

                            if ($flag) {
                                $visibile=1;
                            }
                            else {
                                $visibile=0;
                            }

                            $query = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoria . '","' . $url_key . '","' . $sesso . '","' . $parent . '","' . $i . '","1","' . $visibile . '")';
                            $writeConnection->query($query);

                            $queryENG = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoriaEng . '","' . $url_keyEng . '","' . $sesso . '","' . $parent . '","' . $i . '","2","' . $visibile . '")';
                            $writeConnection->query($queryENG);

                            $queryUSA = 'insert into ' . $resource->getTableName("categorie_menu") . ' (id,nome,url,sesso,parent,posizione,store_id,visibile) values("' . $id_categoria . '","' . $nome_categoriaUsa . '","' . $url_keyUsa . '","' . $sesso . '","' . $parent . '","' . $i . '","3","' . $visibile . '")';
                            $writeConnection->query($queryUSA);
                        }

                    }

            }

        }
        Mage::log("FINITO CATEGORIE",null,$logFileName);

    }
}