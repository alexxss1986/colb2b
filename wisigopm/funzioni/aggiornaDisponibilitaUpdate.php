<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$username = "prova1";
$password = "prova1";
$service_url = "https://api.orderlink.it/v1";

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$filename="aggiornaDisponibilita";
$logFileName= $filename.'.log';

if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

    // tutti i depositi salvati
    $query = "select id from " . $resource->getTableName('wg_warehouse') . "";
    $depositi = $readConnection->fetchAll($query);
    $l=0;
    $depositiAll=array();
    foreach($depositi as $row){
        $depositiAll[$l]=$row["id"];
        $l=$l+1;
    }

    // recupero tutti i prodotti configurabili
    $collection = Mage::getModel('catalog/product')
        ->getCollection()
        ->addAttributeToFilter('type_id','configurable')
        ->addAttributeToFilter('entity_id',array('gt' => 25627));

    foreach ($collection as $product) {
        Mage::log($product->getId(), null, $logFileName);
        $productConfigurable = Mage::getModel("catalog/product")->load($product->getId());

        // per ogni prodotto configurabile recupero lo sku
        // controllo quante occorrenze si ha con quello sku ( se il prodotto è presente su più depositi)

        $service_urlPost = $service_url . "/user/token";
        $curlPost = curl_init($service_urlPost);

        $headersPost = array(
            'Content-Type:application/json',
            'Content-Length: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
            'Authorization: Basic ' . base64_encode($username . ":" . $password)
        );


        curl_setopt($curlPost, CURLOPT_POST, true);  // indico che la richiesta è di tipo POST
        curl_setopt($curlPost, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($curlPost, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
        curl_setopt($curlPost, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlPost, CURLOPT_HTTPHEADER, $headersPost);
        curl_setopt($curlPost, CURLOPT_SSL_VERIFYHOST, false);
        $curl_responsePost = curl_exec($curlPost);
        if ($curl_responsePost === false) {
            $infoPost = curl_getinfo($curlPost);
            curl_close($curlPost);
            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPost));
        }
        curl_close($curlPost);
        $decodedPost = json_decode($curl_responsePost);


        if (is_object($decodedPost)) {
            $arrayPost = get_object_vars($decodedPost);
        }

        $aToken = $arrayPost["access_token"];

        $headersGet = array(
            'Authorization: Bearer ' . $aToken
        );



        $service_urlGet = $service_url . "/stocks?id=".$product->getSku();
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
            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoGet));
        }
        curl_close($curlGet);
        $decodedGet = json_decode($curl_responseGet);

        if (is_object($decodedGet)) {
            $decodedGet = get_object_vars($decodedGet);
        }


        $qtaTotale=array();
        $magazzinoArray=array();
        $inStock = false;

        foreach ($decodedGet as $key => $value) {
            // recupero lo sku del prodotto configurabile
            // recupero tutte le varianti, il totale qta e il deposito
            $id = $key;
            $valoreStocks = $value;

            if (is_array($valoreStocks)) {

                for ($k = 0; $k < count($valoreStocks); $k++) {

                    $total = null;
                    $varianti = null;
                    $deposit_id = null;


                    foreach ($valoreStocks[$k] as $key => $value) {
                        // recupero campi prodotto
                        if ($key == "total") {
                            $total = $value;
                        }
                        if ($key == "scalars") {
                            $varianti = $value;
                            if (is_object($varianti)) {
                                $varianti = get_object_vars($varianti);
                            }
                        }
                        if ($key == "deposit_id") {
                            $deposit_id = $value;
                        }
                    }


                    // controllo esistenza prodotto in magento (dovrebbe sempre esistere!!)
                    $sku_configurabile = $id;
                    $product_id = Mage::getModel("catalog/product")->getIdBySku($sku_configurabile);

                    if ($product_id) {
                        $prodottoConfigurabile = Mage::getModel("catalog/product")->load($product_id);

                        // recupero l'id del deposito in magento
                        $query = "select id from " . $resource->getTableName('wg_warehouse') . " where code = '" . $deposit_id . "'";
                        $idDepositoMage = $readConnection->fetchOne($query);
                        if ($idDepositoMage == null) {

                        } else {


                            // recupero ogni variante
                            $r = 0;
                            foreach ($varianti as $key => $value) {
                                $misuraDispo = $value;

                                if (is_object($misuraDispo)) {
                                    $misuraDispo = get_object_vars($misuraDispo);
                                }

                                foreach ($misuraDispo as $key2 => $value2) {
                                    $misura = $key2;
                                    $disponibilita = $value2;

                                    // recupero lo sku del prodotto semplice
                                    $sku_semplice = $id . "-" . strtolower($misura);
                                    $product_id = Mage::getModel("catalog/product")->getIdBySku($sku_semplice);
                                    $productSimple = Mage::getModel("catalog/product")->load($product_id);

                                    // controllo esistenza quantità prodotto nella tabella magazzini
                                    $query = "select qty from " . $resource->getTableName('wg_warehouse_product') . " where warehouse_id = '" . $idDepositoMage . "' and product_id = '" . $product_id . "'";
                                    $qtyMagazzino = $readConnection->fetchOne($query);

                                    $flag = false;

                                    if ($qtyMagazzino == null) {
                                        // se la qta non esiste allora la metto nel database dei magazzino
                                        // setto flag a true per indicare che è stata fatta una modifica per il prodotto semplice relativo
                                        $data = Mage::getSingleton('core/date')->gmtDate();
                                        $query = "insert into " . $resource->getTableName('wg_warehouse_product') . " (warehouse_id,product_id,qty,created_at,updated_at) values('" . $idDepositoMage . "','" . $product_id . "','" . $disponibilita . "','" . $data . "','" . $data . "')";
                                        $writeConnection->query($query);
                                        $flag = true;

                                    } else {

                                        // aggiorno la qta solo se è effettivamente cambiata
                                        if ($qtyMagazzino != $disponibilita) {
                                            $query = "update " . $resource->getTableName('wg_warehouse_product') . " set qty='" . $disponibilita . "' where warehouse_id = '" . $idDepositoMage . "' and product_id = '" . $product_id . "'";
                                            $writeConnection->query($query);
                                            $flag = true;
                                        }
                                    }
                                    // inserisco in un array le qta per ogni prodotto semplice
                                    // ad ogni ciclo di uno stesso configurabile aggiorno l'elemento relativo nell'array
                                    // se siamo all'inizio setto l'elemento dell'array a 0
                                    if (!isset($qtaTotale[$r])) {
                                        $qtaTotale[$r] = 0;
                                    }
                                    $qtaTotale[$r] = $qtaTotale[$r] + $disponibilita;


                                    if ($flag == true) {
                                        // aggiorno la qta del prodotto su magento solo se sono state fatte modifiche
                                        // calcolo il totale quantità per il prodotto
                                        $query = "select SUM(qty) from " . $resource->getTableName('wg_warehouse_product') . " where product_id = '" . $product_id . "'";
                                        $qtyTot = $readConnection->fetchOne($query);

                                        // salvo il totale quantità
                                        $stockData = $productSimple->getStockData();
                                        $stockData['qty'] = $qtyTot;

                                        if ($qtyTot > 0) {
                                            $stockData['is_in_stock'] = 1;
                                        } else {
                                            $stockData['is_in_stock'] = 0;
                                        }


                                        $productSimple->setStockData($stockData);
                                        $productSimple->save();
                                    }

                                }
                                $r = $r + 1; // variabile utile per l'array qta di ogni prodotto semplice
                            }
                            $magazzinoArray[$k] = $idDepositoMage; //salvo il magazzino relativo su un array

                        }


                        // se siamo alla fine del prodotto configurabile e quindi abbiamo scorso tutti i magazzini
                        if ($k == count($valoreStocks) - 1) {
                            $ids = $prodottoConfigurabile->getTypeInstance()->getUsedProductIds();
                            $m = 0;
                            // recupero tutti i prodotti semplici
                            foreach ($ids as $id) {
                                $productSimple = Mage::getModel('catalog/product')->load($id);
                                $query = "select SUM(qty) from " . $resource->getTableName('wg_warehouse_product') . " where product_id = '" . $id . "'";
                                $qtyTot = $readConnection->fetchOne($query);
                                // controllo quanta quantità è salvata nel db per il prodotto e la confronto con quella effetivamente ritornata dal WS
                                if ($qtyTot != $qtaTotale[$m]) {
                                    // se è diversa significa che alcuni magazzini sono stati eliminati
                                    // li trovvo facendo l'array diff di tutti i magazzini con quelli recuperati dal WS per il prodotto
                                    $magazziniNot = array_values(array_diff($depositiAll, $magazzinoArray));
                                    for ($t = 0; $t < count($magazziniNot); $t++) {
                                        // per ogni magazzino insesistente elimino la voce nel DB
                                        $query = "delete from " . $resource->getTableName('wg_warehouse_product') . " where warehouse_id='" . $magazziniNot[$t] . "'";
                                        $writeConnection->query($query);
                                    }

                                    // salvo il totale quantità in Magento contanto le ultime modifiche
                                    $stockData = $productSimple->getStockData();
                                    $stockData['qty'] = $qtaTotale[$m];

                                    if ($qtyTot > 0) {
                                        $stockData['is_in_stock'] = 1;
                                    } else {
                                        $stockData['is_in_stock'] = 0;
                                    }

                                    $productSimple->setStockData($stockData);
                                    $productSimple->save();
                                }

                                $m = $m + 1; // increment per i prodotti semplici
                            }

                            // recupero la quantità totale per il prodotto configurabile
                            $qtaSum = array_sum($qtaTotale);
                            $stockItem = $prodottoConfigurabile->getStockItem();
                            // se è >0 setto inStock a true
                            if ($qtaSum > 0 && $prodottoConfigurabile->getSmallImage() != null && $prodottoConfigurabile->getSmallImage() != "no_selection") {
                                $inStock = true;
                            }
                            // se lo stock del prodotto salvato in magento è diverso a quello calcolato dalla risposta del WS
                            // salvo lo stock del prodotto configurabile
                            if ($stockItem->getIsInStock() != $inStock) {
                                $stockData = $productConfigurable->getStockData();
                                $stockData['is_in_stock'] = $inStock;
                                $productConfigurable->setStockData($stockData);
                                $productConfigurable->save();
                            }

                            // azzero le variabili usate
                            $magazzinoArray = array();
                            $qtaTotale = array();
                            $inStock = false;
                        }
                    }

                }
            }

        }



    }

    Mage::log("FINE", null, $logFileName);

}