<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$username = "prova1";
$password = "prova1";
$service_url = "https://api.orderlink.it/v1";

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {


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
    $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=500";
    $curlGet = curl_init($service_urlGet);
    curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
    curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
    curl_setopt($curlGet, CURLOPT_HEADER, true);
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlGet , CURLOPT_NOBODY, true);
    curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
    $header = curl_exec($curlGet);

    if ($header === false) {
        $infoGet = curl_getinfo($curlGet);
        curl_close($curlGet);
        die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoGet));
    }

    // costrusico un array dell'header e recupero il totale delle pagine
    $myarray=array();
    $data=explode("\n",$header);
    $myarray['status']=$data[0];
    array_shift($data);
    foreach($data as $part){
        $middle=explode(":",$part);
        if (isset($middle[1])){
            $myarray[trim($middle[0])] = trim($middle[1]);
        }
    }
    $pagine=$myarray["X-Count-Pages"];


    // effettuo una chiamata al web service per ogni pagina
    Mage::log("TOT PAGINE ".$pagine);
    for ($p=5; $p<=$pagine; $p++) {
        Mage::log("pagina ".$p);
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

        $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=500&page=".$p;
        $curlGet = curl_init($service_urlGet);
        curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
        curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
        curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
        curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
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

        $l=1;
        foreach ($decodedGet as $key => $value) {
            Mage::log($l);
            $l=$l+1;
            $id = $key;
            $valoreProdotti = $value;

            $variant=null;
            $descrizione=null;
            $codice_colore_fornitore=null;
            $alternative_ids=null;
            $codice_produttore=null;
            $nome_brand=null;
            $nome_anno=null;
            $nome_stagione=null;
            $id_season=null;
            $id_categoria=null;
            $nome_categoria=null;
            $id_sottocategoria1=null;
            $nome_sottocategoria1=null;
            $id_sottocategoria2=null;
            $nome_sottocategoria2=null;
            $id_sottocategoria3=null;
            $nome_sottocategoria3=null;
            $attributi=null;
            $varianti=null;

            foreach ($valoreProdotti as $key => $value) {
                if ($key=="description"){
                    $descrizione = $value;
                }
                if ($key=="variant"){
                    $variant = $value;
                    if (is_object($variant)) {
                        $variant = get_object_vars($variant);
                    }
                    foreach ($variant as $key => $value) {
                        $codice_colore_fornitore = $value;
                    }
                }
                if ($key=="alternative_ids") {
                    $alternative_ids = $value;
                    if (is_object($alternative_ids)) {
                        $alternative_ids = get_object_vars($alternative_ids);
                    }
                }


                // recupero campi prodotto

                if ($key=="brand"){
                    $brand = $value;
                    if (is_object($brand)) {
                        $brand = get_object_vars($brand);
                    }
                    foreach ($brand as $key => $value) {
                        $id_brand = $key;
                        $nome_brand = $value;
                    }
                }
                if ($key=="season"){
                    $season = $value;
                    if (is_object($season)) {
                        $season = get_object_vars($season);
                    }
                    foreach ($season as $key => $value) {
                        $id_season = $key;
                        $nome_season = $value;
                        $array_season=explode("/",$nome_season);
                        $nome_anno=trim($array_season[0]);
                        $nome_stagione=trim($array_season[1]);
                    }

                }
                if ($key=="macro_category"){
                    $categoria = $value;
                    if (is_object($categoria)) {
                        $categoria = get_object_vars($categoria);
                    }
                    foreach ($categoria as $key => $value) {
                        $id_categoria = $key;
                        $nome_categoria = $value;
                    }
                }
                if ($key=="group"){
                    $sottocategoria1 = $value;
                    if (is_object($sottocategoria1)) {
                        $sottocategoria1 = get_object_vars($sottocategoria1);
                    }
                    foreach ($sottocategoria1 as $key => $value) {
                        $id_sottocategoria1 = $key;
                        $nome_sottocategoria1 = $value;
                    }
                }
                if ($key=="subgroup"){
                    $sottocategoria2 = $value;
                    if (is_object($sottocategoria2)) {
                        $sottocategoria2 = get_object_vars($sottocategoria2);
                    }
                    foreach ($sottocategoria2 as $key => $value) {
                        $id_sottocategoria2 = $key;
                        $nome_sottocategoria2 = $value;
                    }
                }
                if ($key=="category"){
                    $sottocategoria3 = $value;
                    if (is_object($sottocategoria3)) {
                        $sottocategoria3 = get_object_vars($sottocategoria3);
                    }
                    foreach ($sottocategoria3 as $key => $value) {
                        $id_sottocategoria3 = $key;
                        $nome_sottocategoria3 = $value;
                    }
                }
                if ($key=="attributes") {
                    $attributi = $value;
                    if (is_object($attributi)) {
                        $attributi = get_object_vars($attributi);
                    }
                }

                if ($key=="scalars") {
                    $varianti = $value;
                    if (is_object($varianti)) {
                        $varianti = get_object_vars($varianti);
                    }
                }


            }


            if ($id_brand != null && $id_season != null && $id_categoria != null && $id_sottocategoria1 != null && $id_sottocategoria2 != null) {
                // ATTRIBUTO BRAND


                // recupero codice produttore
                if ($alternative_ids!=null && count($alternative_ids)>1) {
                    $codice_produttore = $alternative_ids[1];
                }
                else if ($alternative_ids!=null && count($alternative_ids)>0){
                    $codice_produttore = $alternative_ids[0];
                }


                // recupero attributi prodotto

                foreach ($attributi as $key=>$value) {
                    $id_attributo=$key;
                    $valoreAttributi=$value;

                    foreach ($valoreAttributi as $key => $value) {
                        if ($key=="description") {
                            $nome_attributo = $value;
                        }
                        if ($key=="values") {
                            $subattributes = $value;
                        }
                    }

                    if ($nome_attributo == "Supercolore" || $nome_attributo=="Made In" || $nome_attributo=="Composizione") {

                        // controllo esistenza dell'attributo in magento
                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                        $id_attributoMage = $readConnection->fetchOne($stringQuery);
                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);


                        // se l'attributo è il supercolore utilizzo anche l'attributo colore
                        if ($nome_attributo == "Supercolore") {
                            $stringaIdAttributo = "";
                            $stringaValoreAttributo = "";

                            $j=0;
                            // costruisco il colore misto separando i colori con "/"
                            foreach ($subattributes as $key => $value) {

                                $id_valoreattributo = $key;
                                $nome_valoreattributo = $value;


                                if ($j != 0) {
                                    $stringaValoreAttributo .= "/";
                                    $stringaIdAttributo .= "/";
                                }
                                $stringaValoreAttributo .= $nome_valoreattributo;
                                $stringaIdAttributo .= $id_valoreattributo;

                                $j=$j+1;

                            }

                            // controllo esistenza opzione in magento per l'attributo in questione
                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $stringaIdAttributo . "' and id_attributes='" . $id_attributo . "'";
                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                            if ($id_valoreattributoMage == null) {
                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                $attribute = $attribute_model->load($attribute_code);

                                $attribute->setData('option', array(
                                    'value' => array(
                                        'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                    )
                                ));
                                $attribute->save();

                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                $optionTable = $setup->getTable('eav/attribute_option');

                                $id_valoreattributoMage = getLastInsertId($optionTable, 'option_id');

                                $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "','" . $id_attributo . "')";
                                $writeConnection->query($query);

                                if ($j == 1) {
                                    // se il prodotto ha un solo colore, salvo questo colore anche nell'attributo "ca_colore" e salvo l'associazione nella tabella corrispondente
                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_colore');
                                    $attribute = $attribute_model->load($attribute_code);

                                    $attribute->setData('option', array(
                                        'value' => array(
                                            'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                        )
                                    ));
                                    $attribute->save();



                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                    $optionTable = $setup->getTable('eav/attribute_option');

                                    $id_valoreattributoMage = getLastInsertId($optionTable, 'option_id');

                                    $query = "insert into " . $resource->getTableName('wsca_colore') . " (id_magento,id_ws,nome_magento) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "','" . ucfirst(strtolower($stringaValoreAttributo)) . "')";
                                    $writeConnection->query($query);



                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                    $attribute = $attribute_model->load($attribute_code);

                                    $attribute->setData('option', array(
                                        'value' => array(
                                            'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                        )
                                    ));
                                    $attribute->save();


                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                    $optionTable = $setup->getTable('eav/attribute_option');

                                    $id_valoreattributoMage = getLastInsertId($optionTable, 'option_id');

                                    $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "')";
                                    $writeConnection->query($query);
                                } else {
                                    // se il prodotto ha più colori, salverò nell'attributo colore la dicitura Colori misti (se non esiste già)
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                    $id_coloriMisti = $readConnection->fetchOne($stringQuery);

                                    if ($id_coloriMisti == null) {
                                        // Colori misti non esiste
                                        $attribute_model = Mage::getModel('eav/entity_attribute');
                                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                        $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_colore');
                                        $attribute = $attribute_model->load($attribute_code);

                                        $attribute->setData('option', array(
                                            'value' => array(
                                                'option' => array("Colori misti", "Colori misti")
                                            )
                                        ));
                                        $attribute->save();




                                        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                        $optionTable = $setup->getTable('eav/attribute_option');

                                        $id_coloriMistiMage = getLastInsertId($optionTable, 'option_id');

                                        $query = "insert into " . $resource->getTableName('wsca_colore') . " (id_magento,id_ws,nome_magento) values('" . $id_coloriMistiMage . "','0','Colori misti')";
                                        $writeConnection->query($query);
                                    }


                                    // se il prodotto ha più colori, salverò nell'attributo filtraggio_colore tutti i colori presenti (se non esiste già)
                                    $filtraggio_colori=explode("/",$stringaIdAttributo);
                                    $filtraggio_coloriName=explode("/",$stringaValoreAttributo);
                                    for ($u=0; $u<count($filtraggio_colori); $u++) {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='".$filtraggio_colori[$u]."'";
                                        $id_filtraggioColoreMage = $readConnection->fetchOne($stringQuery);

                                        if ($id_filtraggioColoreMage == null) {
                                            // Colori misti non esiste
                                            $attribute_model = Mage::getModel('eav/entity_attribute');
                                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                            $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                            $attribute = $attribute_model->load($attribute_code);

                                            $attribute->setData('option', array(
                                                'value' => array(
                                                    'option' => array($filtraggio_coloriName[$u], $filtraggio_coloriName[$u])
                                                )
                                            ));
                                            $attribute->save();


                                            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                            $optionTable = $setup->getTable('eav/attribute_option');

                                            $id_filtraggioColoreMage = getLastInsertId($optionTable, 'option_id');

                                            $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_filtraggioColoreMage . "','" . $filtraggio_colori[$u] . "')";
                                            $writeConnection->query($query);
                                        }
                                    }
                                }


                            } else {
                                $attr =  Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                                if ($attr->usesSource()) {
                                    $nomeSuperColoreMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                    if (strtolower($nomeSuperColoreMage)!=strtolower($stringaValoreAttributo)){
                                        $attribute_model = Mage::getModel('eav/entity_attribute');
                                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                        $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                        $attribute = $attribute_model->load($attribute_code);

                                        // modifica della stagione su magento (nome dell'opzione)
                                        $data = array();
                                        $values = array(
                                            $id_valoreattributoMage => array(
                                                0 => ucfirst(strtolower($stringaValoreAttributo)),
                                                1 => ucfirst(strtolower($stringaValoreAttributo))
                                            ),

                                        );

                                        $data['option']['value'] = $values;
                                        $attribute->addData($data);


                                        $attribute->save();

                                        if ($j == 1) {
                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where LOWER(nome_magento)='".strtolower($nomeSuperColoreMage)."'";
                                            $id_coloreMage = $readConnection->fetchOne($stringQuery);

                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
                                                    $attribute = $attribute_model->load($attribute_code);

                                                    // modifica della stagione su magento (nome dell'opzione)
                                                    $data = array();
                                                    $values = array(
                                                        $id_coloreMage => array(
                                                            0 => ucfirst(strtolower($stringaValoreAttributo)),
                                                            1 => ucfirst(strtolower($stringaValoreAttributo))
                                                        ),

                                                    );

                                                    $data['option']['value'] = $values;
                                                    $attribute->addData($data);


                                                    $attribute->save();
                                        }

                                    }
                                }
                                if ($j == 1) {
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='".$stringaIdAttributo."'";
                                    $id_filtraggio_coloreMage = $readConnection->fetchOne($stringQuery);

                                    if ($id_filtraggio_coloreMage==null) {
                                        $attribute_model = Mage::getModel('eav/entity_attribute');
                                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                        $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                        $attribute = $attribute_model->load($attribute_code);

                                        $attribute->setData('option', array(
                                            'value' => array(
                                                'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                            )
                                        ));
                                        $attribute->save();


                                        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                        $optionTable = $setup->getTable('eav/attribute_option');

                                        $id_valoreattributoMage = getLastInsertId($optionTable, 'option_id');

                                        $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "')";
                                        $writeConnection->query($query);
                                    }
                                }
                                else {
                                    // se il prodotto ha più colori, salverò nell'attributo filtraggio_colore tutti i colori presenti (se non esiste già)
                                    $filtraggio_colori=explode("/",$stringaIdAttributo);
                                    $filtraggio_coloriName=explode("/",$stringaValoreAttributo);
                                    for ($u=0; $u<count($filtraggio_colori); $u++) {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='".$filtraggio_colori[$u]."'";
                                        $id_filtraggioColoreMage = $readConnection->fetchOne($stringQuery);

                                        if ($id_filtraggioColoreMage == null) {
                                            // Colori misti non esiste
                                            $attribute_model = Mage::getModel('eav/entity_attribute');
                                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                            $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                            $attribute = $attribute_model->load($attribute_code);

                                            $attribute->setData('option', array(
                                                'value' => array(
                                                    'option' => array($filtraggio_coloriName[$u], $filtraggio_coloriName[$u])
                                                )
                                            ));
                                            $attribute->save();


                                            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                            $optionTable = $setup->getTable('eav/attribute_option');

                                            $id_filtraggioColoreMage = getLastInsertId($optionTable, 'option_id');

                                            $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_filtraggioColoreMage . "','" . $filtraggio_colori[$u] . "')";
                                            $writeConnection->query($query);
                                        }
                                    }
                                }


                            }

                        }
                    }
                }


                // recupero varianti
                foreach ($varianti as $key => $value) {
                    $scalare = $key;
                    $misura = $value;

                    // inserimento prodotto semplice
                    $sku_semplice = $id . "-" . strtolower($misura);



                    $array_sku[$countP] = $sku_semplice; //inserisco in un array tutti gli sku inseriti
                    $countP = $countP + 1;

                    $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                    if (!$productSimple) {

                    } else {

                        $attributes = $productSimple->getAttributes();
                        foreach ($attributes as $attribute) {
                            $attributeCode = $attribute->getAttributeCode();
                            if ($attributeCode=="ca_000001" || $attributeCode=="ca_colore" || $attributeCode=="ca_filtraggio_colore") {
                                $productSimple->setData($attributeCode, "");
                            }
                        }


                        $productSimple->save();


                        $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);

                        $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setData('ca_codice_colore_fornitore', $codice_colore_fornitore);
                        $productSimple->setData('ca_codice_produttore', $codice_produttore);

                        // associazione attributi custom al prodotto semplice
                        foreach ($attributi as $key=>$value) {
                            $id_attributo=$key;
                            $valoreAttributi=$value;

                            foreach ($valoreAttributi as $key => $value) {
                                if ($key=="description") {
                                    $nome_attributo = $value;
                                }
                                if ($key=="values") {
                                    $subattributes = $value;
                                }
                            }

                            if ($nome_attributo == "Supercolore" || $nome_attributo=="Made In" || $nome_attributo=="Composizione") {
                                if ($nome_attributo == "Supercolore") {
                                    $id_valoreattributo = "";
                                    $j=0;
                                    foreach ($subattributes as $key => $value) {
                                        if ($j != 0) {
                                            $id_valoreattributo .= "/";
                                        }
                                        $id_valoreattributo .= $key;
                                        $j=$j+1;
                                    }

                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                    if ($j == 1) {

                                        foreach ($subattributes as $key => $value) {
                                            $id_valoreattributo = $key;
                                        }

                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                        $nome_attributoMage = "ca_colore";
                                        $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                        $nome_attributoMage = "ca_filtraggio_colore";
                                        $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);


                                    } else {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                        $nome_attributoMage = "ca_colore";
                                        $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                        $filtraggioColoriArray=explode("/",$id_valoreattributo);
                                        $stringa_valori="";
                                        for ($u=0; $u<count($filtraggioColoriArray); $u++) {
                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='".$filtraggioColoriArray[$u]."'";
                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                            if ($u != 0) {
                                                $stringa_valori .= ",";
                                            }
                                            $stringa_valori .= $id_valoreattributoMage;

                                        }

                                        $nome_attributoMage = "ca_filtraggio_colore";
                                        $productSimple->setData($nome_attributoMage, $stringa_valori);
                                    }
                                } else {
                                    $stringa_valori = "";
                                    $j = 0;
                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;
                                    }


                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productSimple->setData($nome_attributoMage, $nome_valoreattributo);

                                }
                            }

                        }


                        $productSimple->save();
                    }

                }


                // inserimento prodotto configurabile
                $sku_configurabile = $id;






                $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);
                if (!$productConfigurable) {
                    Mage::log("NON ESISTE ".$sku_configurabile);

                } else {
                    $attributes = $productConfigurable->getAttributes();
                    foreach ($attributes as $attribute) {
                        $attributeCode = $attribute->getAttributeCode();
                        if ($attributeCode=="ca_000001" || $attributeCode=="ca_colore" || $attributeCode=="ca_filtraggio_colore") {
                            $productConfigurable->setData($attributeCode, "");
                        }
                    }


                    $productConfigurable->save();


                    $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);

                    $productConfigurable->setDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setShortDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setData('ca_codice_colore_fornitore', $codice_colore_fornitore);
                    $productConfigurable->setData('ca_codice_produttore', $codice_produttore);


                    // associazione attributi custom al prodotto configurabile
                    foreach ($attributi as $key=>$value) {
                        $id_attributo=$key;
                        $valoreAttributi=$value;

                        foreach ($valoreAttributi as $key => $value) {
                            if ($key=="description") {
                                $nome_attributo = $value;
                            }
                            if ($key=="values") {
                                $subattributes = $value;
                            }
                        }

                        if ($nome_attributo == "Supercolore" || $nome_attributo=="Made In" || $nome_attributo=="Composizione") {
                            if ($nome_attributo == "Supercolore") {
                                $id_valoreattributo = "";
                                $j=0;
                                foreach ($subattributes as $key => $value) {
                                    if ($j != 0) {
                                        $id_valoreattributo .= "/";
                                    }
                                    $id_valoreattributo .= $key;
                                    $j=$j+1;
                                }

                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                if ($j == 1) {

                                    foreach ($subattributes as $key => $value) {
                                        $id_valoreattributo = $key;
                                    }

                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_colore";
                                    $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_filtraggio_colore";
                                    $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);


                                } else {
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_colore";
                                    $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                    $filtraggioColoriArray=explode("/",$id_valoreattributo);
                                    $stringa_valori="";
                                    for ($u=0; $u<count($filtraggioColoriArray); $u++) {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='".$filtraggioColoriArray[$u]."'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                        if ($u != 0) {
                                            $stringa_valori .= ",";
                                        }
                                        $stringa_valori .= $id_valoreattributoMage;

                                    }

                                    $nome_attributoMage = "ca_filtraggio_colore";
                                    $productConfigurable->setData($nome_attributoMage, $stringa_valori);
                                }
                            } else {
                                $stringa_valori = "";
                                $j = 0;
                                foreach ($subattributes as $key => $value) {

                                    $id_valoreattributo = $key;
                                    $nome_valoreattributo = $value;
                                }


                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                $productConfigurable->setData($nome_attributoMage, $nome_valoreattributo);

                            }
                        }
                    }

                    $productConfigurable->save();



                }





            }


        }

        $files = glob('../../var/images/*.*');
        foreach($files as $file)
            unlink($file);


    }
    Mage::log("FINE");
}
else {
    Mage::log("WS Import Catalogo: Parametri non specificati");
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

// Download Image
function  getDownloadImage($type,$file,$sottoCat,$nome_brand,$nome_colore,$id){
    /*$path = str_replace("index.php","",$_SERVER["SCRIPT_FILENAME"]);
    $import_location = $path.'media/catalog/';
    if (!file_exists($import_location)){
        mkdir($import_location, 0755);
    }
    $import_location = $path.'media/catalog/'.$type.'/';
    if (!file_exists($import_location)){
        mkdir($import_location, 0755);
    }*/

    // estensione foto
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    // recupero il numero della foto
    $punto = strrpos($file, ".");
    $file_new = substr($file, 0, $punto);

    // il numero dell'immagine
    $numero_img = substr($file_new, strlen($file_new) - 1, 1);
    $nome_file=replace_accents(url_slug(strtolower($sottoCat)))."_".replace_accents(url_slug(strtolower($nome_brand)))."_".replace_accents(url_slug(strtolower($nome_colore)))."_".replace_accents(url_slug(strtolower($id)))."-".$numero_img.".".$ext;

    $import_location="../../var/images";

    $file_source = Mage::getStoreConfig('oscommerceimportconf/oscconfiguration/conf_imageurl',Mage::app()->getStore()).$file;
    $file_target = $import_location."/".$nome_file;

    $file_source=str_replace(" ","%20",$file_source);

    $file_path = "";
    if (($file != '') and (!file_exists($file_target))){
        $rh = fopen($file_source, 'rb');
        $wh = fopen($file_target, 'wb');
        if ($rh===false || $wh===false) {
            // error reading or opening file
            $file_path = "";
        }
        else {
            while (!feof($rh)) {
                if (fwrite($wh, fread($rh, 1024)) === FALSE) {
                    $file_path = $file_target;
                }
            }
        }
        fclose($rh);
        fclose($wh);
    }
    if (file_exists($file_target)){
        if ($type == 'category'){
            $file_path = $file;
        }else{
            $file_path = $file_target;
        }
    }

    return $file_path;
}


function url_slug($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );

    // Merge options
    $options = array_merge($defaults, $options);

    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }

    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);

    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}



