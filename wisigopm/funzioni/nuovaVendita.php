<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

                $resource = Mage::getSingleton('core/resource');
                $readConnection = $resource->getConnection('core_read');
                $writeConnection = $resource->getConnection('core_write');


            $filename = "nuovaVendita";
            $logFileName = $filename . '.log';

            $filename2 = "erroreVendita";
            $logFileName2 = $filename2 . '.log';


            $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter(
                    array(
                        'status',
                        'status'
                    ),
                    array(
                        array('eq' => 'complete'),
                        array('eq' => 'processing')
                    )
                )
            ;

            foreach ($orders as $ordine) {
                try {



                    if ($ordine->getIncrementId()=="200000067") {
                        Mage::log($ordine->getIncrementId(),null,$logFileName);
                        $order = Mage::getModel('sales/order')->loadByIncrementId($ordine->getIncrementId());

                        $id_ordine = $order->getIncrementId();



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
                                            $magazzinoArray[] = $wid;
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
                                        'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                        'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                        'a.notarangelo@coltorti.it' => 'Notarangelo',
                                        'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                        'a.notarangelo@coltorti.it' => 'Notarangelo',
                                        'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
                                    );
                                }
                                if ($magazzinoArray[$i] == 6) {
                                    $template_id = 'template_ordine_boutique_centrale_consolidato';
                                    $email_to = array(
                                        'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                        'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                        'a.notarangelo@coltorti.it' => 'Notarangelo',
                                        'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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

                            /*if ($emailInviata == false) {
                                $template_id = 'template_ordine_boutique_centrale_consolidato';
                                $email_to = array(
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                            }*/
                        } else {

                            if ($magazzinoArray[0] == 1) {
                                $template_id = 'template_ordine_boutique_diretto';
                                $email_to = array(
                                    'r.mazzaro@coltorti.it' => 'Regina Mazzaro Responsabile',
                                    'l.bartelucci@coltorti.it' => 'Leonardo Bartelucci Back Office',
                                    'd.bartolucci@coltorti.it' => 'Diego Bartolucci Supporto',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
                                );
                            }
                            if ($magazzinoArray[0] == 6) {
                                $template_id = 'template_ordine_boutique_centrale_diretto';
                                $email_to = array(
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'c.ercolani@coltorti.it' => 'Caterina Ercolani',
                                    'e.magrini@coltorti.it' => 'Enrico Magrini Responsabile',
                                    'a.notarangelo@coltorti.it' => 'Notarangelo',
                                    'andrea.sebastianelli@threec.com' => 'Andrea Sebastianelli'
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


                    }


                } catch (SoapFault $fault) {
                    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
                }


            }


        }

