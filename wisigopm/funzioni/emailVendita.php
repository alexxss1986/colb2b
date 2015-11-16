<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $order=Mage::getModel('sales/order')->loadByIncrementId("200000014");


                    $id_ordine = $order->getIncrementId();


                    $warehouses = Mage::getModel('wgmulti/warehouse')
                        ->getCollection()
                        ->addOrder('position', 'ASC');
                    $warehouseData = $warehouses->toFlatArray();

$i=0;
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

                    // elimino i duplicati per sapere quanti magazzini sono stati utilizzati
                    $magazzinoArray = array_unique($magazzinoArray);

                    $emailInviata = false;
                    if (count($magazzinoArray) > 1) {
                        for ($i = 0; $i < count($magazzinoArray); $i++) {


                            // Who were sending to...
                            if ($magazzinoArray[$i] == 1) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = 'andrea.sebastianelli89@gmail.com';
                                $customer_name = "Boutique Jesi";
                            }
                            if ($magazzinoArray[$i] == 2) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = 'andrea.sebastianelli89@gmail.com';
                                $customer_name = "Boutique San Benedetto del Tronto";
                            }
                            if ($magazzinoArray[$i] == 3) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = 'andrea.sebastianelli89@gmail.com';
                                $customer_name = "Boutique Macerata";
                            }
                            if ($magazzinoArray[$i] == 4) {
                                $template_id = 'template_ordine_boutique_consolidato';
                                $email_to = 'andrea.sebastianelli89@gmail.com';
                                $customer_name = "Boutique Ancona";
                            }
                            if ($magazzinoArray[$i] == 6) {
                                $template_id = 'template_ordine_boutique_centrale_consolidato';
                                $email_to = 'andrea.sebastianelli89@gmail.com';
                                $customer_name = "Magazzino Centrale";
                                $emailInviata = true;
                            }


                            // Load our template by template_id
                            /*$email_template = Mage::getModel('core/email_template')->loadDefault($template_id);

                            // Here is where we can define custom variables to go in our email template!


                            // I'm using the Store Name as sender name here.
                            $sender_name = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
                            // I'm using the general store contact here as the sender email.
                            $sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
                            $email_template->setSenderName($sender_name);
                            $email_template->setSenderEmail($sender_email);
                            //Send the email!
                            $email_template->send($email_to, $customer_name, $email_template_variables);*/

                            $email_template_variables = array(
                                'id_order' => $id_ordine
                            );

                            $templateClient = Mage::getModel('core/email_template') ->loadByCode($template_id)->getTemplateId();
                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                    $templateClient,
                                    Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                    $email_to,
                                    null,
                                    $email_template_variables
                                );



                        }

                        if ($emailInviata == false) {
                            $template_id = 'template_ordine_boutique_centrale_consolidato';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Magazzino Centrale";


                            // Load our template by template_id
                           /* $email_template = Mage::getModel('core/email_template')->loadDefault($template_id);

                            // Here is where we can define custom variables to go in our email template!
                            $email_template_variables = array(
                                'id_order' => $id_ordine
                                // Other variables for our email template.
                            );

                            // I'm using the Store Name as sender name here.
                            $sender_name = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
                            // I'm using the general store contact here as the sender email.
                            $sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
                            $email_template->setSenderName($sender_name);
                            $email_template->setSenderEmail($sender_email);

                            //Send the email!
                            $email_template->send($email_to, $customer_name, $email_template_variables);*/

                            $email_template_variables = array(
                                'id_order' => $id_ordine
                            );


                            $templateClient = Mage::getModel('core/email_template') ->loadByCode($template_id)->getTemplateId();
                            $mailTemplate = Mage::getModel('core/email_template');
                            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                ->sendTransactional(
                                    $templateClient,
                                    Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                    $email_to,
                                    null,
                                    $email_template_variables
                                );
                        }
                    } else {

                        // Who were sending to...
                        if ($magazzinoArray[0] == 1) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Boutique Jesi";
                        }
                        if ($magazzinoArray[0] == 2) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Boutique San Benedetto del Tronto";
                        }
                        if ($magazzinoArray[0] == 3) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Boutique Macerata";
                        }
                        if ($magazzinoArray[0] == 4) {
                            $template_id = 'template_ordine_boutique_diretto';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Boutique Ancona";
                        }
                        if ($magazzinoArray[0] == 6) {
                            $template_id = 'template_ordine_boutique_centrale_diretto';
                            $email_to = 'andrea.sebastianelli89@gmail.com';
                            $customer_name = "Magazzino Centrale";
                        }


                        // Load our template by template_id
                       /* $email_template = Mage::getModel('core/email_template')->loadDefault($template_id);

                        // Here is where we can define custom variables to go in our email template!
                        $email_template_variables = array(
                            'id_order' => $id_ordine
                        );

                        // I'm using the Store Name as sender name here.
                        $sender_name = Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);
                        // I'm using the general store contact here as the sender email.
                        $sender_email = Mage::getStoreConfig('trans_email/ident_general/email');
                        $email_template->setSenderName($sender_name);
                        $email_template->setSenderEmail($sender_email);

                        //Send the email!
                        $email_template->send($email_to, $customer_name, $email_template_variables);*/

                        $email_template_variables = array(
                            'id_order' => $id_ordine
                        );

                        $templateClient = Mage::getModel('core/email_template') ->loadByCode($template_id)->getTemplateId();
                        $mailTemplate = Mage::getModel('core/email_template');
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->sendTransactional(
                                $templateClient,
                                Mage::getStoreConfig("contacts/email/sender_email_identity"),
                                $email_to,
                                null,
                                $email_template_variables
                            );
                    }


        }