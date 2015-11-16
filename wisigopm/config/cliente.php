<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])) {

    if (isset($_REQUEST['id'])) {

        $id = $_REQUEST['id'];

        include("percorsoMage.php");
        require_once "../" . $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $stringQuery = "select id_cliente from " . $resource->getTableName('cliente_vip') . " where id_cliente='" . $id . "'";
        $id_cliente = $readConnection->fetchOne($stringQuery);

        if ($id_cliente != null) {
            $query = "delete from " . $resource->getTableName('cliente_vip') . "  where id_cliente='" . $id . "'";
            $writeConnection->query($query);

            if ($query) {
                echo "<script>alert('Operazione eseguita con successo.');location.replace('../utenti.php')</script>";
            } else {
                echo "<script>alert('Errore!!');location.replace('../utenti.php')</script>";
            }
        } else {
            $query = "insert into " . $resource->getTableName('cliente_vip') . "  (id_cliente) values ('" . $id . "')";
            $writeConnection->query($query);

            if ($query) {
                $customer=Mage::getModel('customer/customer')->load($id);
                $nome=$customer->getFirstname(); // First Name
                $cognome=$customer->getLastname(); // Middle Name
                $email=$customer->getEmail();
                $sesso=$customer->getGender();
                $data=$customer->getDob();

                $postObject["nome"]=$nome;
                $postObject["cognome"]=$cognome;
                $postObject["email"]=$email;


                $customerAddressId=$customer->getDefaultBilling();
                $stato= "";
                $provincia="";
                $cap="";
                $citta="";
                $telefono="";
                $indirizzo="";
                if ($customerAddressId) {
                    $address = Mage::getModel('customer/address')->load($customerAddressId);
                    $stato= $address->getCountryId();
                    $provincia=$address->getRegionId();
                    $cap=$address->getPostcode();
                    $citta=$address->getCity();
                    $telefono=$address->getTelephone();
                    $indirizzo=$address->getStreet();
                }


                $template = Mage::getModel('core/email_template') ->loadByCode('template_vip_accedi')->getTemplateId();

                $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->sendTransactional(
                        $template,
                        Mage::getStoreConfig('contacts/email/sender_email_identity'),
                        $email,
                        null,
                        array('nome' => $nome,
                            'cognome' => $cognome,
                            'email' => $email,
                            'data' => $data,
                            'sesso' => $sesso,
                            'stato' => $stato,
                            'provincia' => $provincia,
                            'cap' => $cap,
                            'citta' => $citta,
                            'telefono' => $telefono,
                            'indirizzo' => $indirizzo[0])
                    );



                echo "<script>alert('Operazione eseguita con successo.');location.replace('../utenti.php')</script>";

            } else {
                echo "<script>alert('Errore!!');location.replace('../utenti.php')</script>";
            }
        }

    }
    else {
        echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}

