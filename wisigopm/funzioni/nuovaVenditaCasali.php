<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $order = Mage::getModel('sales/order')->loadByIncrementId("200000438");
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

                        /*$emailInviata = false;
                        if (count($magazzinoArray) > 1) {
                            for ($i = 0; $i < count($magazzinoArray); $i++) {

                                if ($magazzinoArray[$i] == 1) {
                                    $template_id = 'template_ordine_boutique_consolidato';
                                    $email_to = array(
                                        'r.mazzaro@coltorti.it' => 'Regina Mazzaro Responsabile',
                                        'l.bartelucci@coltorti.it' => 'Leonardo Bartelucci Back Office',
                                        'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                        'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                        'a.notarangelo@coltorti.it' => 'Notarangelo'
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
                                        'a.notarangelo@coltorti.it' => 'Notarangelo'
                                    );
                                }
                                if ($magazzinoArray[$i] == 3) {
                                    $template_id = 'template_ordine_boutique_consolidato';
                                    $email_to = array(
                                        'l.gironella@coltorti.it' => 'Lucia Gironella Responsabile',
                                        'v.dellefoglie@coltorti.it' => 'Valeria Delle Foglie Back Office',
                                        'd.coppari@coltorti.it' => 'Daniele Coppari Supporto',
                                        'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                        'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                        'a.notarangelo@coltorti.it' => 'Notarangelo'
                                    );
                                }
                                if ($magazzinoArray[$i] == 4) {
                                    $template_id = 'template_ordine_boutique_consolidato';
                                    $email_to = array(
                                        's.catini@coltorti.it' => 'Sabrina Catini Responsabile',
                                        'a.tarini@coltorti.it' => 'Alessandro Tarini Back Office',
                                        'f.cotoloni@coltorti.it' => 'Francesca Cotoloni Supporto',
                                        'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                        'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                        'a.notarangelo@coltorti.it' => 'Notarangelo'
                                    );
                                }
                                if ($magazzinoArray[$i] == 6) {
                                    $template_id = 'template_ordine_boutique_centrale_consolidato';
                                    $email_to = array(
                                        'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                        'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                        'a.notarangelo@coltorti.it' => 'Notarangelo'
                                    );

                                    $emailInviata=true;
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
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
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
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
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
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
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
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
                                );
                            }
                            if ($magazzinoArray[0] == 4) {
                                $template_id = 'template_ordine_boutique_diretto';
                                $email_to = array(
                                    's.catini@coltorti.it' => 'Sabrina Catini Responsabile',
                                    'a.tarini@coltorti.it' => 'Alessandro Tarini Back Office',
                                    'f.cotoloni@coltorti.it' => 'Francesca Cotoloni Supporto',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
                                );
                            }
                            if ($magazzinoArray[0] == 6) {
                                $template_id = 'template_ordine_boutique_centrale_diretto';
                                $email_to = array(
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo'
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
                        }*/

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




