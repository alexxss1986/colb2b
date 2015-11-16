<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

    function getLastCategoryEng($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

        return $_category;
    }


    function getLastCategoryIta($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->load($_categories[0]);

        return $_category;
    }


        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {




            try {
                $idConf=65519;

                        $productITA = Mage::getModel('catalog/product')->load($idConf);
                        $nome_brandIta = $productITA->getAttributeText("ca_brand");

                        $category = getLastCategoryIta($productITA);
                        $nome_sottocategoriaIta = $category->getName();


                        $url_keyITA = strtolower($nome_sottocategoriaIta . "-" . $nome_brandIta . "-" . $productITA->getSku());

                        echo $url_keyITA."<br>";


                        $productITA->setUrlKey($url_keyITA);
                        $productITA->getResource()->saveAttribute($productITA, 'url_key');



                        $productEng = Mage::getModel('catalog/product')->setStoreId(2)->load($idConf);
                        $nome_brandEng = $productEng->getAttributeText("ca_brand");

                        $category = getLastCategoryEng($productEng);
                        $nome_sottocategoriaEng = $category->getName();

                        $url_keyEng = strtolower($nome_sottocategoriaEng . "-" . $nome_brandEng . "-" . $productEng->getSku());

                echo $url_keyEng."<br>";
                        $productEng->setUrlKey($url_keyEng);
                        $productEng->getResource()->saveAttribute($productEng, 'url_key');



                        $productUsa = Mage::getModel('catalog/product')->setStoreId(3)->load($productEng->getId());
                        $productUsa->setUrlKey($url_keyEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'url_key');






            }
            catch (Exception $e){
                Mage::log("ERRORE ".$e->getMessage(), null, $logFileName);
            }
        }
        else {
            Mage::log("WS Import Catalogo: Parametri non specificati");
        }
