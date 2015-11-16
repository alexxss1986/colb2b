<?php
class Webgriffe_Multiwarehouse_Model_Observer
{

    /**
     * event: catalog_product_save_before
     *
     * @param Varien_Event_Observer $observer
     */
    public function handleMultipleQuantitiesPost(Varien_Event_Observer $observer)
    {
        if (!Mage::app()->getRequest()->isPost()) {
            return;
        }

        if (!isset($post['wgmulti_original_use_multiple_qty']) && !isset($post['wgmulti_use_multiple_qty'])){
            return;
        }

        /** @var Mage_Catalog_Model_Product $product */
        $product = $observer->getProduct();

        $post = Mage::app()->getRequest()->getPost();

        // if multiple quantity was disabled
        if ($post['wgmulti_original_use_multiple_qty'] == 1 && $post['wgmulti_use_multiple_qty'] == 0)
        {
            Mage::getModel('wgmulti/warehouse_product')
                ->getCollection()
                ->addProductIdFilter($product->getId())
                ->delete();
        }

        // if multiple quantity was enabled
        if ($post['wgmulti_use_multiple_qty'] == 1)
        {
            $totalQty = 0.0;
            foreach ($post['wgmultiqty'] as $warehouseId => $qty)
            {
                Mage::getModel('wgmulti/warehouse_product')
                    ->getCollection()
                    ->addWarehouseIdFilter($warehouseId)
                    ->addProductIdFilter($product->getId())
                    ->getFirstItem() // if doesn't exist, return new object
                    ->setWarehouseId($warehouseId)
                    ->setProductId($product->getId())
                    ->setQty($qty)
                    ->save();
                $totalQty += $qty;
            }
            $product->getStockItem()->setQty($totalQty);
        }
    }

    /**
     * event: sales_model_service_quote_submit_success
     */
    public function decrementQuantities(Varien_Event_Observer $observer)
    {

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            // aggiornamento prodotti ordinati nei magazzini
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $writeConnection = $resource->getConnection('core_write');

            // tutti i depositi salvati
            $query = "select id from " . $resource->getTableName('wg_warehouse') . "";
            $depositi = $readConnection->fetchAll($query);
            $l = 0;
            $depositiAll = array();
            foreach ($depositi as $row) {
                $depositiAll[$l] = $row["id"];
                $l = $l + 1;
            }


            /** @var Mage_Sales_Model_Order $order */
            $order = $observer->getOrder();


            /** @var Mage_Sales_Model_Order_Item $item */
            foreach ($order->getAllItems() as $item) {
                // check disponibilità magazzini
                $idProdottoSemplice = Mage::getModel('catalog/product')->getIdBySku($item->getSku());
                if ($idProdottoSemplice != "") {
                    // recupero il colore del prodotto semplice associato all'etichetta
                    $idProdottoConfigurabile = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($idProdottoSemplice);

                    if ($idProdottoConfigurabile[0] != "") {
                        $productConfigurable = Mage::getModel('catalog/product')->load($idProdottoConfigurabile[0]);

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
                            Mage::log("ERRORE NELLA CONNESSIONE");
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


                        $service_urlGet = $service_url . "/stocks?id=" . $productConfigurable->getSku();
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
                            Mage::log("ERRORE NELLA CONNESSIONE");
                        }
                        curl_close($curlGet);
                        $decodedGet = json_decode($curl_responseGet);

                        if (is_object($decodedGet)) {
                            $decodedGet = get_object_vars($decodedGet);
                        }


                        $qtaTotale = array();
                        $magazzinoArray = array();
                        $inStock = false;

                        foreach ($decodedGet as $key => $value) {
                            // recupero lo sku del prodotto configurabile
                            // recupero tutte le varianti, il totale qta e il deposito
                            $id = $key;
                            $valoreStocks = $value;

                            if (is_array($valoreStocks)) {

                                for ($k = 0; $k < count($valoreStocks); $k++) {

                                    $varianti = null;
                                    $deposit_id = null;


                                    foreach ($valoreStocks[$k] as $key => $value) {
                                        // recupero campi prodotto
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



                                        // recupero l'id del deposito in magento
                                        $query = "select id from " . $resource->getTableName('wg_warehouse') . " where code = '" . $deposit_id . "'";
                                        $idDepositoMage = $readConnection->fetchOne($query);
                                        if ($idDepositoMage == null) {

                                        } else {


                                            // recupero ogni variante

                                            foreach ($varianti as $key => $value) {
                                                $misuraDispo = $value;

                                                if (is_object($misuraDispo)) {
                                                    $misuraDispo = get_object_vars($misuraDispo);
                                                }

                                                foreach ($misuraDispo as $key2 => $value2) {
                                                    $misura = $key2;
                                                    $disponibilita = $value2;
                                                    $indice = strtolower($misura);

                                                    if ($disponibilita<0){
                                                        $disponibilita=0;
                                                    }

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
                                                    if (!isset($qtaTotale[$indice])) {
                                                        $qtaTotale[$indice] = 0;
                                                    }
                                                    $qtaTotale[$indice] = $qtaTotale[$indice] + $disponibilita;


                                                    if ($flag == true) {
                                                        // aggiorno la qta del prodotto su magento solo se sono state fatte modifiche
                                                        // calcolo il totale quantità per il prodotto
                                                        $query = "select SUM(qty) from " . $resource->getTableName('wg_warehouse_product') . " where product_id = '" . $product_id . "'";
                                                        $qtyTot = $readConnection->fetchOne($query);

                                                        // salvo il totale quantità
                                                        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productSimple->getId());
                                                        $stockItem->setData('qty', $qtyTot);
                                                        if ($qtyTot > 0) {
                                                            $stockItem->setData('is_in_stock', 1);
                                                        } else {
                                                            $stockItem->setData('is_in_stock', 0);
                                                        }

                                                        try {
                                                            $stockItem->save();
                                                        } catch (Exception $e) {
                                                            Mage::log("ERRORE 1 ". $e->getMessage());
                                                        }
                                                    }

                                                }

                                            }
                                            $magazzinoArray[$k] = $idDepositoMage; //salvo il magazzino relativo su un array


                                            // se siamo alla fine del prodotto configurabile e quindi abbiamo scorso tutti i magazzini
                                            if ($k == count($valoreStocks) - 1) {
                                                $ids = $productConfigurable->getTypeInstance()->getUsedProductIds();

                                                // recupero tutti i prodotti semplici
                                                foreach ($ids as $id) {
                                                    $productSimple = Mage::getModel('catalog/product')->load($id);
                                                    $indiceP = strtolower($productSimple->getAttributeText("ca_misura"));
                                                    $query = "select SUM(qty) from " . $resource->getTableName('wg_warehouse_product') . " where product_id = '" . $id . "'";
                                                    $qtyTot = $readConnection->fetchOne($query);
                                                    // controllo quanta quantità è salvata nel db per il prodotto e la confronto con quella effetivamente ritornata dal WS
                                                    if (number_format($qtyTot, 0) != $qtaTotale[$indiceP]) {
                                                        // se è diversa significa che alcuni magazzini sono stati eliminati
                                                        // li trovvo facendo l'array diff di tutti i magazzini con quelli recuperati dal WS per il prodotto
                                                        $magazziniNot = array_values(array_diff($depositiAll, $magazzinoArray));
                                                        for ($t = 0; $t < count($magazziniNot); $t++) {
                                                            // per ogni magazzino insesistente elimino la voce nel DB
                                                            $query2 = "delete from " . $resource->getTableName('wg_warehouse_product') . " where warehouse_id='" . $magazziniNot[$t] . "' and product_id = '" . $id . "'";
                                                            $writeConnection->query($query2);
                                                        }

                                                        // salvo il totale quantità in Magento contanto le ultime modifiche
                                                        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productSimple->getId());
                                                        $stockItem->setData('qty', $qtaTotale[$indiceP]);
                                                        if ($qtaTotale[$indiceP] > 0) {
                                                            $stockItem->setData('is_in_stock', 1);
                                                        } else {
                                                            $stockItem->setData('is_in_stock', 0);
                                                        }

                                                        try {
                                                            $stockItem->save();
                                                        } catch (Exception $e) {
                                                            Mage::log("ERRORE 2" . $e->getMessage());
                                                        }
                                                    }


                                                    else if ($qtaTotale[$indiceP] != number_format($productSimple->getStockItem()->getQty(),0)){
                                                        // salvo il totale quantità in Magento contanto le ultime modifiche

                                                        $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productSimple->getId());
                                                        $stockItem->setData('qty', $qtaTotale[$indiceP]);
                                                        if ($qtaTotale[$indiceP] > 0) {
                                                            $stockItem->setData('is_in_stock', 1);
                                                        } else {
                                                            $stockItem->setData('is_in_stock', 0);
                                                        }

                                                        try {
                                                            $stockItem->save();
                                                        } catch (Exception $e) {
                                                            Mage::log("ERRORE 2" . $e->getMessage());
                                                        }
                                                    }

                                                }

                                                // recupero la quantità totale per il prodotto configurabile
                                                $qtaSum = array_sum($qtaTotale);
                                                $stockItem = $productConfigurable->getStockItem();
                                                // se è >0 setto inStock a true
                                                if ($qtaSum > 0 && $productConfigurable->getSmallImage() != null && $productConfigurable->getSmallImage() != "no_selection") {
                                                    $inStock = true;
                                                }
                                                // se lo stock del prodotto salvato in magento è diverso a quello calcolato dalla risposta del WS
                                                // salvo lo stock del prodotto configurabile
                                                if ($stockItem->getIsInStock() != $inStock) {

                                                    $stockItemConf = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productConfigurable->getId());
                                                    $stockItemConf->setData('is_in_stock', $inStock);

                                                    try {
                                                        $stockItemConf->save();
                                                    } catch (Exception $e) {
                                                        Mage::log("ERRORE 3 ". $e->getMessage());
                                                    }
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
                }


                $additionalData = array();
                $serializedAdditionalData = $item->getAdditionalData();
                if (!empty($serializedAdditionalData)) {
                    $additionalData = unserialize($serializedAdditionalData);
                }

                $orderedQty = $item->getQtyOrdered();

                /** @var Webgriffe_Multiwarehouse_Model_Resource_Warehouse_Product_Collection $warehouseProducts */
                $warehouseProducts = Mage::getModel('wgmulti/warehouse_product')
                    ->getCollection()
                    ->addProductIdFilter($item->getProductId())
                    ->addWarehousePositionOrder();

                foreach ($warehouseProducts as $warehouseProduct) {
                    if ($warehouseProduct->getQty() >= $orderedQty) {
                        $warehouseProduct->setQty($warehouseProduct->getQty() - $orderedQty);
                        $additionalData[$warehouseProduct->getWarehouseId()] = $orderedQty;
                        break;
                    }
                    $additionalData[$warehouseProduct->getWarehouseId()] = $warehouseProduct->getQty();
                    $orderedQty -= $warehouseProduct->getQty();
                    $warehouseProduct->setQty(0);
                }

                $item->setAdditionalData(serialize($additionalData))->save();

                $warehouseProducts->save();

            }

            $this->nuovaVendita($observer,$username,$password,$service_url);
        }
    }


    public function nuovaVendita($observer,$username,$password,$service_url){
        $order = $observer->getOrder();
        if ($order->getState() == Mage_Sales_Model_Order::STATE_PROCESSING) {

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $writeConnection = $resource->getConnection('core_write');
            try {

                $id_ordine = $order->getIncrementId();

                $stringQuery = "select id_ws_ordine from " . $resource->getTableName('wsca_ordini') . " where id_ordine='" . $id_ordine . "'";
                $id_ws = $readConnection->fetchOne($stringQuery);

                if ($id_ws==null || $id_ws=="") {

                    $i = 0;

                    $filename = "nuovaVendita";
                    $logFileName = $filename . '.log';

                    $filename2 = "erroreVendita";
                    $logFileName2 = $filename2 . '.log';


                    $warehouses = Mage::getModel('wgmulti/warehouse')
                        ->getCollection()
                        ->addOrder('position', 'ASC');
                    $warehouseData = $warehouses->toFlatArray();



                    $magazzinoArray = array();
                    $_items = $order->getAllItems();
                    foreach ($_items as $item) {

                        $serializedAdditionalData = $item->getAdditionalData();
                        if (empty($serializedAdditionalData)) {

                        } else {
                            $additionalData = unserialize($serializedAdditionalData);


                            foreach ($warehouseData as $wid => $wdata) {
                                if (array_key_exists($wid, $additionalData)) {
                                    $qty = $additionalData[$wid];
                                    if ($qty > 0) {
                                        $magazzinoArray[$i] = $wid;
                                        $i = $i + 1;
                                    }
                                }
                            }
                        }

                    }
                    $magazzinoArray = array_unique($magazzinoArray);
                    $magazzinoArray = array_values($magazzinoArray);

                    $emailInviata = false;
                    if (count($magazzinoArray) > 1) {
                        for ($i = 0; $i < count($magazzinoArray); $i++) {

                            if ($magazzinoArray[$i] == 1) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = array(
                                    'r.mazzaro@coltorti.it' => 'Regina Mazzaro Responsabile',
                                    'l.bartelucci@coltorti.it' => 'Leonardo Bartelucci Back Office',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );
                            }
                            if ($magazzinoArray[$i] == 2) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = array(
                                    'a.isopi@coltorti.it' => 'Alessandra Isopi Responsabile',
                                    'g.vitangeli@coltorti.it' => 'Gessica Vitangeli Back Office',
                                    'f.silenzi@coltorti.it' => 'Francesco Silenzi Supporto',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    't.troiano@coltorti.it' => 'Antonio Troiano',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );
                            }
                            if ($magazzinoArray[$i] == 3) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = array(
                                    'l.gironella@coltorti.it' => 'Lucia Gironella Responsabile',
                                    'v.dellefoglie@coltorti.it' => 'Valeria Delle Foglie Back Office',
                                    'l.ciucci@coltorti.it' => 'Laura Ciucci Supporto',
                                    'd.coppari@coltorti.it' => 'Daniele Coppari Supporto',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );
                            }
                            if ($magazzinoArray[$i] == 4) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = array(
                                    's.catini@coltorti.it' => 'Sabrina Catini Responsabile',
                                    'f.cotoloni@coltorti.it' => 'Francesca Cotoloni Supporto',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'l.polenta@coltorti.it' => 'Leonardo Polenta',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );
                            }
                            if ($magazzinoArray[$i] == 6) {
                                $template_id = 'template_ordine_boutique_centrale_consolidato';
                                $email_to = array(
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );

                                $emailInviata=true;
                            }
                            if ($magazzinoArray[$i] == 8) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = array(
                                    'r.mancini@coltorti.it' => 'Roberto Mancini Backoffice',
                                    'l.doneddu@coltorti.it' => 'Lorenzo Doneddu Responsabile',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'm.cotichella@coltorti.it' => 'Cotichella'
                                );
                            }


                            $email_template_variables = array(
                                'id_order' => $id_ordine
                            );

                            $templateClient = Mage::getModel('core/email_template')->loadByCode($template_id)->getTemplateId();
                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                    $templateClient,
                                    Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                    array_keys($email_to),
                                    array_values($email_to),
                                    $email_template_variables
                                );


                        }

                        if ($emailInviata == false) {
                            $template_id = 'template_ordine_boutique_centrale_consolidato';
                            $email_to = array(
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );

                            $email_template_variables = array(
                                'id_order' => $id_ordine
                            );


                            $templateClient = Mage::getModel('core/email_template')->loadByCode($template_id)->getTemplateId();
                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                    $templateClient,
                                    Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                    array_keys($email_to),
                                    array_values($email_to),
                                    $email_template_variables
                                );
                        }
                    } else {

                        if ($magazzinoArray[0] == 1) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = array(
                                'r.mazzaro@coltorti.it' => 'Regina Mazzaro Responsabile',
                                'l.bartelucci@coltorti.it' => 'Leonardo Bartelucci Back Office',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }
                        if ($magazzinoArray[0] == 2) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = array(
                                'a.isopi@coltorti.it' => 'Alessandra Isopi Responsabile',
                                'g.vitangeli@coltorti.it' => 'Gessica Vitangeli Back Office',
                                'f.silenzi@coltorti.it' => 'Francesco Silenzi Supporto',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                't.troiano@coltorti.it' => 'Antonio Troiano',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }
                        if ($magazzinoArray[0] == 3) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = array(
                                'l.gironella@coltorti.it' => 'Lucia Gironella Responsabile',
                                'v.dellefoglie@coltorti.it' => 'Valeria Delle Foglie Back Office',
                                'l.ciucci@coltorti.it' => 'Laura Ciucci Supporto',
                                'd.coppari@coltorti.it' => 'Daniele Coppari Supporto',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }
                        if ($magazzinoArray[0] == 4) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = array(
                                's.catini@coltorti.it' => 'Sabrina Catini Responsabile',
                                'f.cotoloni@coltorti.it' => 'Francesca Cotoloni Supporto',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'l.polenta@coltorti.it' => 'Leonardo Polenta',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }
                        if ($magazzinoArray[0] == 6) {
                            $template_id = 'template_ordine_boutique_centrale_diretto';
                            $email_to = array(
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }
                        if ($magazzinoArray[0] == 8) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = array(
                                'r.mancini@coltorti.it' => 'Roberto Mancini Backoffice',
                                'l.doneddu@coltorti.it' => 'Lorenzo Doneddu Responsabile',
                                'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                'a.notarangelo@coltorti.it' => 'Notarangelo',
                                'm.cotichella@coltorti.it' => 'Cotichella'
                            );
                        }


                        $email_template_variables = array(
                            'id_order' => $id_ordine
                        );

                         $templateClient = Mage::getModel('core/email_template')->loadByCode($template_id)->getTemplateId();
                         $mailTemplate = Mage::getModel('core/email_template');
                         $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                             ->sendTransactional(
                                 $templateClient,
                                 Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                 array_keys($email_to),
                                 array_values($email_to),
                                 $email_template_variables
                             );
                    }

                    $arrayProdotti = array();
                    $_items = $order->getItemsCollection();
                    $i = 0;
                    foreach ($_items as $item) {
                        $productId = Mage::getModel('catalog/product')->getIdBySku($item->getSku());
                        $product = Mage::getModel('catalog/product')->load($productId);

                        $scalare = $product->getData("ca_scalare");

                        $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                        if (isset($parentIds[0])) {
                            $parent = Mage::getModel('catalog/product')->load($parentIds[0]);
                        }
                        $idProduct = $parent->getSku();

                        $serializedAdditionalData = $item->getAdditionalData();
                        if (empty($serializedAdditionalData)) {

                        } else {
                            $additionalData = unserialize($serializedAdditionalData);


                            foreach ($warehouseData as $wid => $wdata) {
                                if (array_key_exists($wid, $additionalData)) {
                                    $qty = $additionalData[$wid];
                                    if ($qty > 0) {
                                        $id_deposito = $wid;
                                        $dep = Mage::getModel('wgmulti/warehouse')->load($id_deposito);
                                        $id_dep = $dep->getCode();
                                        $quantita = $qty;
                                        $arrayQty = array($scalare => '' . $quantita . '');
                                        $arrayProdotti[] = array("id" => $idProduct, "deposit_id" => $id_dep, "quantity" => $arrayQty);
                                        $i = $i + 1;
                                    }
                                }
                            }
                        }

                    }


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


                    $service_urlPost = $service_url . "/orders";
                    $curlPost = curl_init($service_urlPost);

                    $arrayCustomer = array(
                        "name" => "3C S.r.l",
                        "surname" => "3C S.r.l",
                        "address" => "Via G. B. Pergolesi, 22",
                        "zip" => "20124",
                        "city" => "Milano",
                        "state" => "IT",
                        "country" => "MI",
                        "phone" => "023313429",
                        "mobile" => "",
                        "email" => "info@threec.com"
                    );


                    $fields = array(
                        'order_id' => $id_ordine,
                        'customer' => $arrayCustomer,
                        'products' => $arrayProdotti
                    );


                    $fields_string = json_encode($fields);




                    $headersPost = array(
                        'Content-Type:application/json',
                        'Content-Length: ' . strlen($fields_string),
                        'Authorization: Bearer ' . $aToken
                    );


                    curl_setopt($curlPost, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($curlPost, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                    curl_setopt($curlPost, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curlPost, CURLOPT_HTTPHEADER, $headersPost);
                    curl_setopt($curlPost, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curlPost, CURLOPT_POST, 1);
                    curl_setopt($curlPost, CURLOPT_POSTFIELDS, $fields_string);
                    $curl_responsePost = curl_exec($curlPost);
                    $httpcode = curl_getinfo($curlPost, CURLINFO_HTTP_CODE);
                    if ($curl_responsePost === false) {
                        $infoPost = curl_getinfo($curlPost);
                        curl_close($curlPost);
                        die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPost));
                    }
                    curl_close($curlPost);



                    if ($httpcode!="201") {
                        $stringaErrore = "Errore nell'ordine ".$order->getIncrementId();


                        Mage::log($stringaErrore, null, $logFileName2);

                        $email_template_variables = array(
                            'errore' => $stringaErrore,
                            'id_order' => $id_ordine
                        );


                            $email_to = array(
                                'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli',
                            );


                        $templateClient = Mage::getModel('core/email_template')->loadByCode("errore_vendita")->getTemplateId();
                        $mailTemplate = Mage::getModel('core/email_template');
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->sendTransactional(
                                $templateClient,
                                Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                array_keys($email_to),
                                array_values($email_to),
                                $email_template_variables
                            );

                        $id_vendita=0;
                    }
                    else {
                        $id_vendita=1;
                    }


                    $stringQuery = "insert into " . $resource->getTableName('wsca_ordini') . " (id_ordine,id_ws_ordine) value ('" . $id_ordine . "','" . $id_vendita . "')";
                    $writeConnection->query($stringQuery);

                    Mage::log("FATTO ".$id_ordine,null,$logFileName);
                }

            } catch (SoapFault $fault) {
                trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
            }


        }
    }
}