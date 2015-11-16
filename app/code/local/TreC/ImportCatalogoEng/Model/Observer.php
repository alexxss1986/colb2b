<?php
class TreC_ImportCatalogoEng_Model_Observer
{
    public function getLastCategoryEng($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

        return $_category;
    }


    public function getLastCategoryIta($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->load($_categories[0]);

        return $_category;
    }

    public function import() {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $filename = "catalogo";
            $logFileName = $filename . '.log';



            try {

                $dataCorrente = date('Y-m-d');

                $stringQuery = "select product_number,running,finish from " . $resource->getTableName('wsca_importeng_log') . " where dataImport='" . $dataCorrente . "'";
                $importLog = $readConnection->fetchAll($stringQuery);
                $product_number = 0;
                $finish = 0;
                $running = 0;
                foreach ($importLog as $row) {
                    $product_number = $row['product_number'];
                    $finish = $row['finish'];
                    $running = $row['running'];
                }

                $product_number=$product_number+1;

                if ($product_number != "" && $running == 0 && $finish == 0) {

                    Mage::log("INIZIO PRODOTTI ENG",null,$logFileName);

                    if ($product_number == 1) {
                        // salvo la pagina in cui sono arrivato
                        $query = "insert into " . $resource->getTableName('wsca_importeng_log') . " (product_number,running,finish,dataImport) values('" . $product_number . "','1','" . $finish . "','" . $dataCorrente . "')";
                        $writeConnection->query($query);
                    } else {
                        // salvo la pagina in cui sono arrivato
                        $query = "update " . $resource->getTableName('wsca_importeng_log') . " set product_number='" . $product_number . "',running='1',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                        $writeConnection->query($query);
                    }


                    // recupero tutti i prodotti configurabili
                    $collection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToFilter("type_id","configurable")
                        ->addAttributeToFilter('entity_id', array('gteq' => $product_number));

                    $count_prodotti = 0;
                    foreach ($collection as $product) {
                        Mage::log($product->getId(),null,$logFileName);
                        $productITA = Mage::getModel('catalog/product')->load($product->getId());
                        $nome_brand=$productITA->getAttributeText("ca_brand");

                        $skuUrl=$productITA->getSku();
                        $skuUrl = str_replace(" ", "%20", $skuUrl);
                        $nome="";
                        $descrizione="";

                        $service_urlPost = $service_url . "/user/token";
                        $curlPost = curl_init($service_urlPost);

                        $headersPost = array(
                            'Content-Type:application/json;charset=utf-8',
                            'Content-Lth: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
                            'Authorization: Basic ' . base64_encode($username . ":" . $password)
                        );


                        curl_setopt($curlPost, CURLOPT_POST, true);  // indico che la richiesta è di tipo POST
                        curl_setopt($curlPost, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($curlPost, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                        curl_setopt($curlPost, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curlPost, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($curlPost, CURLOPT_HTTPHEADER, $headersPost);
                        $curl_responsePost = curl_exec($curlPost);
                        if ($curl_responsePost === false) {
                            $infoPost = curl_getinfo($curl_responsePost);
                            curl_close($curl_responsePost);
                            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPost));
                        }
                        curl_close($curl_responsePost);
                        $decodedPost = json_decode($curl_responsePost);


                        if (is_object($decodedPost)) {
                            $arrayPost = get_object_vars($decodedPost);
                        }

                        $aToken = $arrayPost["access_token"];


                        $headersGet = array(
                            'Authorization: Bearer ' . $aToken
                        );

                        $service_urlGet = $service_url . "/products?lang=it&id=".$skuUrl;
                        $curlGet = curl_init($service_urlGet);
                        curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
                        curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                        curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
                        curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
                        curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
                        $curl_responseGet = curl_exec($curlGet);

                        if ($curl_responseGet === false) {
                            $infoGet = curl_getinfo($curlGet);
                            curl_close($curlGet);

                            break;
                        } else {
                            curl_close($curlGet);

                            // parse del contenuto
                            $decodedGet = json_decode($curl_responseGet);

                            if (is_object($decodedGet)) {
                                $decodedGet = get_object_vars($decodedGet);
                            }


                            foreach ($decodedGet as $key => $value) {
                                $id = $key;
                                $valoreProdotti = $value;


                                $nome = null;
                                $descrizione = null;

                                foreach ($valoreProdotti as $key => $value) {
                                    // recupero campi prodotto
                                    if ($key == "description") {
                                        $descrizione = $value;
                                    }
                                    if ($key == "name") {
                                        $nome = $value;
                                    }
                                }
                            }
                        }


                        $nomeConfigurabile=ucfirst(strtolower($nome_brand . " " . $nome));


                        $productITA->setName($nomeConfigurabile);
                        $productITA->getResource()->saveAttribute($productITA, 'name');
                        $productITA->setDescription(ucfirst(strtolower($descrizione)));
                        $productITA->getResource()->saveAttribute($productITA, 'description');
                        $productITA->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productITA->getResource()->saveAttribute($productITA, 'short_description');
                        $productITA->setCaName($nome);
                        $productITA->getResource()->saveAttribute($productITA, 'ca_name');


                        $count_prodotti = $count_prodotti + 1;


                        if ($count_prodotti == 500) {
                            if ($count_prodotti == count($collection)) {
                                $finish = 1;
                            } else {
                                $finish = 0;
                            }


                            // salvo la pagina in cui sono arrivato
                            $query = "update " . $resource->getTableName('wsca_importeng_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                            $writeConnection->query($query);


                            $readConnection->closeConnection();
                            $writeConnection->closeConnection();

                            break;
                        } else if ($count_prodotti == count($collection)) {
                            $finish = 1;

                            // salvo la pagina in cui sono arrivato
                            $query = "update " . $resource->getTableName('wsca_importeng_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                            $writeConnection->query($query);


                            $readConnection->closeConnection();
                            $writeConnection->closeConnection();

                        }

                    }
                    Mage::log("FINE PRODOTTI ENG",null,$logFileName);
                }

            }
            catch (Exception $e){
                Mage::log("ERRORE ".$e->getMessage(), null, $logFileName);
            }
        }
        else {
            Mage::log("WS Import Catalogo: Parametri non specificati");
        }
    }


}