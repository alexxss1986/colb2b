<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$username = "prova1";
$password = "prova1";
$service_url = "https://api.orderlink.it/v1";

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

try {
    $service_urlPost = $service_url . "/user/token";
    $curlPost = curl_init($service_urlPost);

    $headersPost = array(
        'Content-Type:application/json;charset=utf-8',
        'Content-Length: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
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


// recupero solo l'header della richiesta
    $service_urlGet = $service_url . "/attributes?&limit=100";
    $curlGet = curl_init($service_urlGet);
    curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
    curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
    curl_setopt($curlGet, CURLOPT_HEADER, true);
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlGet, CURLOPT_NOBODY, true);
    curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
    $header = curl_exec($curlGet);

    if ($header === false) {
        $infoGet = curl_getinfo($curlGet);
        curl_close($curlGet);
        die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoGet));
    }

// costrusico un array dell'header e recupero il totale delle pagine
    $myarray = array();
    $data = explode("\n", $header);
    $myarray['status'] = $data[0];
    array_shift($data);
    foreach ($data as $part) {
        $middle = explode(":", $part);
        if (isset($middle[1])) {
            $myarray[trim($middle[0])] = trim($middle[1]);
        }
    }
    $pagine = $myarray["X-Count-Pages"];


// effettuo una chiamata al web service per ogni pagina

    for ($p = 1; $p <= $pagine; $p++) {
        $service_urlPost = $service_url . "/user/token";
        $curlPost = curl_init($service_urlPost);

        $headersPost = array(
            'Content-Type:application/json;charset=utf-8',
            'Content-Length: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
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
        $service_urlGet = $service_url . "/attributes?limit=100&page=" . $p;
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

        // parse del contenuto
        $decodedGet = json_decode($curl_responseGet);


        $countP = 0;
        $array_sku = array("");

        if (is_object($decodedGet)) {
            $decodedGet = get_object_vars($decodedGet);
        }

        $l = 1;
        foreach ($decodedGet as $key => $value) {
            $l = $l + 1;
            $id_attributo = $key;
            $valoreProdotti = $value;

            $description = null;
            $valoriAttributi = null;
            foreach ($valoreProdotti as $key => $value) {
                // recupero campi prodotto
                if ($key == "description") {
                    $nome_attributo = $value;
                    if (is_object($nome_attributo)) {
                        $nome_attributo = get_object_vars($nome_attributo);
                    }
                }
                if ($key == "values") {
                    $subattributes = $value;
                    if (is_object($subattributes)) {
                        $subattributes = get_object_vars($subattributes);
                    }
                }
            }


            // controllo esistenza dell'attributo in magento
            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
            $id_attributoMage = $readConnection->fetchOne($stringQuery);

            Mage::log(count($subattributes));
            if ($id_attributoMage == null) {
                $model = Mage::getModel('eav/entity_setup', 'core_setup');
                if (count($subattributes) == 0) {
                    $input = "text";
                } else {
                    $input = "multiselect";
                }

                $data =
                    array(
                        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'group' => 'General',
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => ucfirst(strtolower(replace_accents($nome_attributo))),
                        'input' => $input,
                        'unique' => false,
                        'required' => false,
                        'is_configurable' => false,
                        'searchable' => false,
                        'visible_in_advanced_search' => false,
                        'comparable' => false,
                        'filterable' => false,
                        'filterable_in_search' => false,
                        'used_for_promo_rules' => false,
                        'visible_on_front' => false,
                        'used_in_product_listing' => false,
                        'used_for_sort_by' => false,
                        'user_defined' => true,

                    );

                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                $model->addAttribute('catalog_product', $nome_attributoMage, $data);


                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                $optionTable = $setup->getTable('eav/attribute');

                $id_attributoMage = getLastInsertId($optionTable, 'attribute_id');

                $query = "insert into " . $resource->getTableName('wsca_attributes') . " (id_magento,id_ws) values('" . $id_attributoMage . "','" . $id_attributo . "')";
                $writeConnection->query($query);

            } else {
                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
            }

            Mage::log($nome_attributo);
            // se l'attributo è il supercolore utilizzo anche l'attributo colore
            if ($nome_attributo == "Supercolore") {


            } else {

                foreach ($subattributes as $key => $value) {

                    $id_valoreattributo = $key;
                    $nome_valoreattributo = $value;

                    // controllo esistenza opzione in magento per l'attributo in questione
                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                    if ($id_valoreattributoMage == null) {
                        if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                        } else {

                            $attribute_model = Mage::getModel('eav/entity_attribute');
                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                            $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                            $attribute = $attribute_model->load($attribute_code);

                            $attribute->setData('option', array(
                                'value' => array(
                                    'option' => array(ucfirst(strtolower($nome_valoreattributo)), ucfirst(strtolower($nome_valoreattributo)))
                                )
                            ));
                            $attribute->save();

                            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                            $optionTable = $setup->getTable('eav/attribute_option');

                            $id_valoreattributoMage = getLastInsertId($optionTable, 'option_id');

                            $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $id_valoreattributoMage . "','" . $id_valoreattributo . "','" . $id_attributo . "')";
                            $writeConnection->query($query);
                        }
                    } else {
                        $attr = Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                        if ($attr->usesSource()) {
                            $nomeValoreAttributoMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                            if (strtolower($nomeValoreAttributoMage) != strtolower($nome_valoreattributo)) {
                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                $attribute = $attribute_model->load($attribute_code);

                                // modifica della stagione su magento (nome dell'opzione)
                                $data = array();
                                $values = array(
                                    $id_valoreattributoMage => array(
                                        0 => ucfirst(strtolower($nome_valoreattributo)),
                                        1 => ucfirst(strtolower($nome_valoreattributo))
                                    ),

                                );

                                $data['option']['value'] = $values;
                                $attribute->addData($data);


                                $attribute->save();
                            }
                        }
                    }

                }

            }

        }


    }
    Mage::log("FINE");
}
catch (Exception $e){
    Mage::log($e->getMessage());
}

function getLastInsertId($tableName, $primaryKey)
{
    //SELECT MAX(id) FROM table
    $db = Mage::getModel('core/resource')->getConnection('core_read');
    $result = $db->raw_fetchRow("SELECT MAX(`{$primaryKey}`) as LastID FROM `{$tableName}`");
    return $result['LastID'];
}

function replace_accents($string)
{
    return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
}