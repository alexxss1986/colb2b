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
    $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&brand_id=BRA481";
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
    for ($p=1; $p<=$pagine; $p++) {
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
        $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&brand_id=BRA481&page=".$p;
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

        $l=1;
        foreach ($decodedGet as $key => $value) {
            Mage::log($l);
            $l=$l+1;
            $id = $key;
            $valoreProdotti = $value;

            $variant=null;
            $name=null;
            $codice_colore_fornitore=null;
            $alternative_ids=null;
            $codice_produttore=null;
            $product_id=null;
            $descrizione=null;
            $prezzo=null;
            $id_brand=null;
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
            $immagini=null;
            $varianti=null;

            foreach ($valoreProdotti as $key => $value) {
                // recupero campi prodotto
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
                if ($key=="product_id"){
                    $product_id = $value;
                }
                if ($key=="description"){
                    $descrizione = $value;
                }
                if ($key=="name"){
                    $nome = $value;
                }
                if ($key=="price"){
                    $prezzo = $value;
                    $prezzo = ($prezzo * 100) / (22 + 100);  // scorporo dell'iva
                }
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
                if ($key=="images") {
                    $immagini = $value;
                    if (is_object($immagini)) {
                        $immagini = get_object_vars($immagini);
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
            if (count($attributi)>0 && count($immagini)>0 && $id_brand != null && $id_season != null && $id_categoria != null && $id_sottocategoria1 != null && $id_sottocategoria2 != null) {
                Mage::log($id);
                // recupero codice produttore
                if ($alternative_ids!=null && count($alternative_ids)>1) {
                    $codice_produttore = $alternative_ids[1];
                }
                else if ($alternative_ids!=null && count($alternative_ids)>0){
                    $codice_produttore = $alternative_ids[0];
                }

                // ATTRIBUTO BRAND

                // recupero id magento associato (se esiste)
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_brand') . " where id_ws='" . $id_brand . "'";
                $id_brandMage = $readConnection->fetchOne($stringQuery);

                if ($id_brandMage == null) {
                    $attribute_model = Mage::getModel('eav/entity_attribute');
                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
                    $attribute = $attribute_model->load($attribute_code);

                    $attribute->setData('option', array(
                        'value' => array(
                            'option' => array(ucfirst(strtolower($nome_brand)), ucfirst(strtolower($nome_brand)))
                        )
                    ));
                    $attribute->save();

                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $optionTable = $setup->getTable('eav/attribute_option');

                    $id_brandMage = getLastInsertId($optionTable, 'option_id');

                    $query = "insert into " . $resource->getTableName('wsca_brand') . " (id_magento,id_ws) values('" . $id_brandMage . "','" . $id_brand . "')";
                    $writeConnection->query($query);

                    //SPLASH
                    $array["ca_brand"]["value"][0]=$id_brandMage;
                    $array["ca_brand"]["operator"]="OR";
                    $array["ca_brand"]["apply_to"]=0;
                    $array["ca_brand"]["include_in_layered_nav"]=0;

                    $opzioniP=serialize($array);

                    $opzioni="a:0:{}";

                    $url_key=url_slug(ucfirst(strtolower($nome_brand)));
                    $url_key=replace_accents($url_key);
                    $status="1";
                    $data=date('Y-m-d H:i:s');

                    $queryPage="select max(page_id) from  ". $resource->getTableName('splash_page');
                    $page_id=$readConnection->fetchOne($queryPage);
                    $page_id=$page_id+1;

                    $query="insert into  ". $resource->getTableName('splash_page') ."  (page_id,name,short_description,description,url_key,option_filters,price_filters,category_filters,status,created_at,updated_at) values ('".$page_id."','".ucfirst(strtolower($nome_brand))."','','','".$url_key."','".$opzioniP."','".$opzioni."','".$opzioni."','".$status."','".$data."','".$data."')";
                    $writeConnection->query($query);

                    $query2="insert into ". $resource->getTableName('splash_page_store') ." (page_id,store_id) values ('".$page_id."','1')";
                    $writeConnection->query($query2);

                    $queryPage="select max(page_id) from  ". $resource->getTableName('splash_page');
                    $page_idEng=$readConnection->fetchOne($queryPage);
                    $page_idEng=$page_idEng+1;

                    $queryEng="insert into ". $resource->getTableName('splash_page') ." (page_id,name,short_description,description,url_key,option_filters,price_filters,category_filters,status,created_at,updated_at) values ('".$page_idEng."','".ucfirst(strtolower($nome_brand))."','','','".$url_key."','".$opzioniP."','".$opzioni."','".$opzioni."','".$status."','".$data."','".$data."')";
                    $writeConnection->query($queryEng);

                    $query2Eng="insert into ". $resource->getTableName('splash_page_store') ." (page_id,store_id) values ('".$page_idEng."','2')";
                    $writeConnection->query($query2Eng);
                }
                else {
                    $attr =  Mage::getModel('catalog/product')->getResource()->getAttribute("ca_brand");
                    if ($attr->usesSource()) {
                        $nomeBrandMage = $attr->getSource()->getOptionText($id_brandMage);
                        if (strtolower($nomeBrandMage)!=strtolower($nome_brand)){
                            $attribute_model = Mage::getModel('eav/entity_attribute');
                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                            $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
                            $attribute = $attribute_model->load($attribute_code);

                            // modifica della stagione su magento (nome dell'opzione)
                            $data = array();
                            $values = array(
                                $id_brandMage => array(
                                    0 => ucfirst(strtolower($nome_brand)),
                                    1 => ucfirst(strtolower($nome_brand))
                                ),

                            );

                            $data['option']['value'] = $values;
                            $attribute->addData($data);


                            $attribute->save();
                        }
                    }
                }

                // ATTRIBUTO ANNO E STAGIONE

                // recupero id magento associato (se esiste)
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_stagione') . " where id_ws='" . $id_season . "'";
                $id_seasonMage = $readConnection->fetchOne($stringQuery);

                if ($id_seasonMage == null) {
                    $attribute_model = Mage::getModel('eav/entity_attribute');
                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_anno");
                    $attribute = $attribute_model->load($attribute_code);

                    $attribute->setData('option', array(
                        'value' => array(
                            'option' => array(ucfirst(strtolower($nome_anno)), ucfirst(strtolower($nome_anno)))
                        )
                    ));
                    $attribute->save();

                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $optionTable = $setup->getTable('eav/attribute_option');

                    $id_annoMage = getLastInsertId($optionTable, 'option_id');

                    $query = "insert into " . $resource->getTableName('wsca_anno') . " (id_magento,id_ws) values('" . $id_annoMage . "','" . $id_season . "')";
                    $writeConnection->query($query);


                    $attribute_model = Mage::getModel('eav/entity_attribute');
                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_stagione");
                    $attribute = $attribute_model->load($attribute_code);

                    $attribute->setData('option', array(
                        'value' => array(
                            'option' => array(ucfirst(strtolower($nome_stagione)), ucfirst(strtolower($nome_stagione)))
                        )
                    ));
                    $attribute->save();

                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $optionTable = $setup->getTable('eav/attribute_option');

                    $id_stagioneMage = getLastInsertId($optionTable, 'option_id');

                    $query = "insert into " . $resource->getTableName('wsca_stagione') . " (id_magento,id_ws) values('" . $id_stagioneMage . "','" . $id_season . "')";
                    $writeConnection->query($query);
                }
                else {
                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_anno') . " where id_ws='" . $id_season . "'";
                    $id_annoMage = $readConnection->fetchOne($stringQuery);

                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_stagione') . " where id_ws='" . $id_season . "'";
                    $id_stagioneMage = $readConnection->fetchOne($stringQuery);

                    $attr =  Mage::getModel('catalog/product')->getResource()->getAttribute("ca_anno");
                    if ($attr->usesSource()) {
                        $nomeAnnoMage = $attr->getSource()->getOptionText($id_annoMage);
                        if (strtolower($nomeAnnoMage)!=strtolower($nome_anno)){
                            $attribute_model = Mage::getModel('eav/entity_attribute');
                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                            $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_anno");
                            $attribute = $attribute_model->load($attribute_code);

                            // modifica della stagione su magento (nome dell'opzione)
                            $data = array();
                            $values = array(
                                $id_annoMage => array(
                                    0 => ucfirst(strtolower($nome_anno)),
                                    1 => ucfirst(strtolower($nome_anno))
                                ),

                            );

                            $data['option']['value'] = $values;
                            $attribute->addData($data);


                            $attribute->save();
                        }
                    }


                    $attr =  Mage::getModel('catalog/product')->getResource()->getAttribute("ca_stagione");
                    if ($attr->usesSource()) {
                        $nomeStagioneMage = $attr->getSource()->getOptionText($id_stagioneMage);
                        if (strtolower($nomeStagioneMage)!=strtolower($nome_stagione)){
                            $attribute_model = Mage::getModel('eav/entity_attribute');
                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                            $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_stagione");
                            $attribute = $attribute_model->load($attribute_code);

                            // modifica della stagione su magento (nome dell'opzione)
                            $data = array();
                            $values = array(
                                $id_stagioneMage => array(
                                    0 => ucfirst(strtolower($nome_stagione)),
                                    1 => ucfirst(strtolower($nome_stagione))
                                ),

                            );

                            $data['option']['value'] = $values;
                            $attribute->addData($data);


                            $attribute->save();
                        }
                    }
                }



                // CATEGORIE

                // 1° LIVELLO CATEGORIA
                // recupero id magento associato (se esiste)
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_macro_category') . " where id_ws='" . $id_categoria . "'";
                $id_categoriaMage = $readConnection->fetchOne($stringQuery);
                if ($id_categoriaMage == null) {
                    $category = Mage::getModel('catalog/category');
                    $category->setStoreId(0);

                    $general['name'] = ucfirst(strtolower($nome_categoria));
                    $general['path'] = "1/2";
                    $general['is_active'] = 1;
                    $general['is_anchor'] = 1;

                    $general['url_key'] = strtolower($nome_categoria);


                    $category->addData($general);
                    $category->save();
                    $id_categoriaMage = $category->getId();

                    $query = "insert into " . $resource->getTableName('wsca_macro_category') . " (id_magento,id_ws) values('" . $id_categoriaMage . "','" . $id_categoria . "')";
                    $writeConnection->query($query);
                }
                else {
                    $categoria=Mage::getModel('catalog/category')->load($id_categoriaMage);
                    $nomeCategoriaMage=$categoria->getName();
                    if (strtolower($nomeCategoriaMage)!=strtolower($nome_categoria)){
                        $general['name'] = ucfirst(strtolower($nome_categoria));
                        $general['url_key'] = strtolower($nome_categoria);
                        $categoria->addData($general);
                        $categoria->save();
                    }
                }

                // 2° LIVELLO CATEGORIA
                // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_group') . " where id_ws='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                $id_sottocategoria1Mage = $readConnection->fetchOne($stringQuery);
                if ($id_sottocategoria1Mage == null) {

                    $categoryPadre = Mage::getModel('catalog/category')->load($id_categoriaMage);
                    $path = $categoryPadre->getPath();

                    $category = Mage::getModel('catalog/category');
                    $category->setStoreId(0);

                    $general['name'] = ucfirst(strtolower($nome_sottocategoria1));
                    $general['path'] = $path;
                    $general['is_active'] = 1;
                    $general['is_anchor'] = 1;

                    $general['url_key'] = strtolower($nome_sottocategoria1) . "-" . strtolower($nome_categoria);


                    $category->addData($general);
                    $category->save();
                    $id_sottocategoria1Mage = $category->getId();

                    $query = "insert into " . $resource->getTableName('wsca_group') . " (id_magento,id_ws,id_macro_category) values('" . $id_sottocategoria1Mage . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                    $writeConnection->query($query);
                }
                else {
                    $sottocategoria1=Mage::getModel('catalog/category')->load($id_sottocategoria1Mage);
                    $nomeSottocategoria1Mage=$sottocategoria1->getName();
                    if (strtolower($nomeSottocategoria1Mage)!=strtolower($nome_sottocategoria1)){
                        $general['name'] = ucfirst(strtolower($nome_sottocategoria1));
                        $general['url_key'] = strtolower($nome_sottocategoria1);
                        $sottocategoria1->addData($general);
                        $sottocategoria1->save();
                    }
                }

                // 3° LIVELLO CATEGORIA
                // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subgroup') . " where id_ws='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                $id_sottocategoria2Mage = $readConnection->fetchOne($stringQuery);
                if ($id_sottocategoria2Mage == null) {

                    $categoryPadre = Mage::getModel('catalog/category')->load($id_sottocategoria1Mage);
                    $path = $categoryPadre->getPath();

                    $category = Mage::getModel('catalog/category');
                    $category->setStoreId(0);

                    $general['name'] = ucfirst(strtolower($nome_sottocategoria2));
                    $general['path'] = $path;
                    $general['is_active'] = 1;
                    $general['is_anchor'] = 1;

                    $general['url_key'] = strtolower($nome_sottocategoria2) . "-" . strtolower($nome_categoria);


                    $category->addData($general);
                    $category->save();
                    $id_sottocategoria2Mage = $category->getId();

                    $query = "insert into " . $resource->getTableName('wsca_subgroup') . " (id_magento,id_ws,id_group,id_macro_category) values('" . $id_sottocategoria2Mage . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                    $writeConnection->query($query);
                }
                else {
                    $sottocategoria2=Mage::getModel('catalog/category')->load($id_sottocategoria2Mage);
                    $nomeSottocategoria2Mage=$sottocategoria2->getName();
                    if (strtolower($nomeSottocategoria2Mage)!=strtolower($nome_sottocategoria2)){
                        $general['name'] = ucfirst(strtolower($nome_sottocategoria2));
                        $general['url_key'] = strtolower($nome_sottocategoria2);
                        $sottocategoria2->addData($general);
                        $sottocategoria2->save();
                    }
                }

       // 4° LIVELLO CATEGORIA
                if ($id_sottocategoria3 != null) {
                    // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_category') . " where id_ws='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                    $id_sottocategoria3Mage = $readConnection->fetchOne($stringQuery);
                    if ($id_sottocategoria3Mage == null) {

                        $categoryPadre = Mage::getModel('catalog/category')->load($id_sottocategoria2Mage);
                        $path = $categoryPadre->getPath();

                        $category = Mage::getModel('catalog/category');
                        $category->setStoreId(0);

                        $general['name'] = ucfirst(strtolower($nome_sottocategoria3));
                        $general['path'] = $path;
                        $general['is_active'] = 1;
                        $general['is_anchor'] = 1;

                        $general['url_key'] = strtolower($nome_sottocategoria3) . "-" . strtolower($nome_categoria);


                        $category->addData($general);
                        $category->save();
                        $id_sottocategoria3Mage = $category->getId();

                        $query = "insert into " . $resource->getTableName('wsca_category') . " (id_magento,id_ws,id_subgroup,id_group,id_macro_category) values('" . $id_sottocategoria3Mage . "','" . $id_sottocategoria3 . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                        $writeConnection->query($query);
                    }
                    else {
                        $sottocategoria3=Mage::getModel('catalog/category')->load($id_sottocategoria3Mage);
                        $nomeSottocategoria3Mage=$sottocategoria3->getName();
                        if (strtolower($nomeSottocategoria3Mage)!=strtolower($nome_sottocategoria3)){
                            $general['name'] = ucfirst(strtolower($nome_sottocategoria3));
                            $general['url_key'] = strtolower($nome_sottocategoria3);
                            $sottocategoria3->addData($general);
                            $sottocategoria3->save();
                        }
                    }
                }


                $array_cat=array();
                // costruisco un array con tutte le categorie
                if ($id_sottocategoria3 != null) {
                    $y = 4;
                    $array_cat[$y] = $id_sottocategoria3Mage;
                    $category = Mage::getModel('catalog/category')->load($id_sottocategoria3Mage);
                    $y = $y - 1;
                    $parent = $category->getParentId();
                    $array_cat[$y] = $parent;
                    $category = Mage::getModel('catalog/category')->load($parent);
                    $parent = $category->getParentId();
                    while ($parent != "1") {
                        $y = $y - 1;
                        $array_cat[$y] = $parent;
                        $category = Mage::getModel('catalog/category')->load($parent);
                        $parent = $category->getParentId();
                    }


                } else if ($id_sottocategoria2 != "") {
                    $y = 3;
                    $array_cat[$y] = $id_sottocategoria2Mage;
                    $category = Mage::getModel('catalog/category')->load($id_sottocategoria2Mage);
                    $y = $y - 1;
                    $parent = $category->getParentId();
                    $array_cat[$y] = $parent;
                    $category = Mage::getModel('catalog/category')->load($parent);
                    $parent = $category->getParentId();
                    while ($parent != "1") {
                        $y = $y - 1;
                        $array_cat[$y] = $parent;
                        $category = Mage::getModel('catalog/category')->load($parent);
                        $parent = $category->getParentId();
                    }


                }

                // recupero immagini
                $immagini_new = array();
                for ($k = 0; $k < count($immagini[1]); $k++) {
                    $immagini_new[$k][0] = $immagini[1][$k];

                    // recupero il numero della foto
                    $punto = strrpos($immagini_new[$k][0], ".");
                    $file_new = substr($immagini_new[$k][0], 0, $punto);

                    // il numero dell'immagine
                    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                    $numero_img = substr($file_new, strlen($file_new) - 1, 1);
                    $immagini_new[$k][1] = $numero_img;
                }

                $z=0;
                $array_cat_new=array();
                // riordino l'array delle categorie
                for ($o = count($array_cat) - 1; $o >= 0; $o--) {
                    $array_cat_new[$z] = $array_cat[$o];
                    $z = $z + 1;
                }



                $supercomposizione="";

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

                    if ($nome_attributo=="Made In" || $nome_attributo=="Composizione") {

                    }
                    else {
                        // controllo esistenza dell'attributo in magento
                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                        $id_attributoMage = $readConnection->fetchOne($stringQuery);

                        if ($id_attributoMage == null) {
                            $model = Mage::getModel('eav/entity_setup', 'core_setup');
                            $input = "multiselect";

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

                        // se l'attributo è il supercolore utilizzo anche l'attributo colore
                        if ($nome_attributo == "Supercolore") {
                            $stringaIdAttributo = "";
                            $stringaValoreAttributo = "";

                            $j = 0;
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

                                $j = $j + 1;

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
                                    $filtraggio_colori = explode("/", $stringaIdAttributo);
                                    $filtraggio_coloriName = explode("/", $stringaValoreAttributo);
                                    for ($u = 0; $u < count($filtraggio_colori); $u++) {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggio_colori[$u] . "'";
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
                                $attr = Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                                if ($attr->usesSource()) {
                                    $nomeSuperColoreMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                    if (strtolower($nomeSuperColoreMage) != strtolower($stringaValoreAttributo)) {
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
                                            $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_colore");
                                            if ($attr->usesSource()) {
                                                $nomeColoreMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                                if (strtolower($nomeColoreMage) != strtolower($stringaValoreAttributo)) {
                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
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
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($j == 1) {
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $stringaIdAttributo . "'";
                                    $id_filtraggio_coloreMage = $readConnection->fetchOne($stringQuery);

                                    if ($id_filtraggio_coloreMage == null) {
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
                                } else {
                                    // se il prodotto ha più colori, salverò nell'attributo filtraggio_colore tutti i colori presenti (se non esiste già)
                                    $filtraggio_colori = explode("/", $stringaIdAttributo);
                                    $filtraggio_coloriName = explode("/", $stringaValoreAttributo);
                                    for ($u = 0; $u < count($filtraggio_colori); $u++) {
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggio_colori[$u] . "'";
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

                        } else {

                            foreach ($subattributes as $key => $value) {

                                $id_valoreattributo = $key;
                                $nome_valoreattributo = $value;


                                // salvo la supercomposizione se c'è
                                if ($nome_attributo == "Supercomposizione") {
                                    $supercomposizione = $nome_valoreattributo;
                                }

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

                // recupero varianti
                foreach ($varianti as $key => $value) {
                    $scalare = $key;
                    $misura = $value;

                    // controllo esistenza opzione in magento per l'attributo in questione
                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_misura') . " where LOWER(misura)='" . strtolower($misura) . "'";
                    $id_misuraMage = $readConnection->fetchOne($stringQuery);
                    if ($id_misuraMage == null) {
                        $attribute_model = Mage::getModel('eav/entity_attribute');
                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
                        $attribute = $attribute_model->load($attribute_code);

                        $attribute->setData('option', array(
                            'value' => array(
                                'option' => array(ucfirst(strtolower($misura)), ucfirst(strtolower($misura)))
                            )
                        ));
                        $attribute->save();

                        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                        $optionTable = $setup->getTable('eav/attribute_option');

                        $id_misuraMage = getLastInsertId($optionTable, 'option_id');

                        $query = "insert into " . $resource->getTableName('wsca_misura') . " (id_magento,misura) values('" . $id_misuraMage . "','" . strtolower($misura) . "')";
                        $writeConnection->query($query);
                    }
                    else {
                        $attr =  Mage::getModel('catalog/product')->getResource()->getAttribute("ca_misura");
                        if ($attr->usesSource()) {
                            $nomeMisuraMage = $attr->getSource()->getOptionText($id_misuraMage);
                            if (strtolower($nomeMisuraMage)!=strtolower($misura)){
                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
                                $attribute = $attribute_model->load($attribute_code);

                                // modifica della stagione su magento (nome dell'opzione)
                                $data = array();
                                $values = array(
                                    $id_misuraMage => array(
                                        0 => ucfirst(strtolower($misura)),
                                        1 => ucfirst(strtolower($misura))
                                    ),

                                );

                                $data['option']['value'] = $values;
                                $attribute->addData($data);


                                $attribute->save();
                            }
                        }
                    }


                    // inserimento delle coppie misura-scalare per ogni macro categoria,gruppo,sottogruppo,categoria e brand

                    $controllo = "select count(*) from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                    $countMisuraScalare = $readConnection->fetchOne($controllo);
                    if ($countMisuraScalare == 0) {
                        $query = "insert into " . $resource->getTableName('wsca_misura_scalare') . " (misura,scalare,id_category,id_subgroup,id_group,id_macro_category,id_brand) values('" . $misura . "','" . $scalare . "','" . $id_sottocategoria3 . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "','" . $id_brand . "')";
                        $writeConnection->query($query);
                    }
                    else {
                        $queryScalare = "select scalare from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                        $scalareMage = $readConnection->fetchOne($stringQuery);
                        if (strtolower($scalareMage)!=strtolower($scalare)) {
                            $query = "update " . $resource->getTableName('wsca_misura_scalare') . "  set scalare='" . $scalare . "' where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                            $writeConnection->query($query);
                        }
                    }


                    // inserimento prodotto semplice
                    $sku_semplice = $id . "-" . strtolower($misura);
                    if ($id_sottocategoria3 != null) {
                        $nome_semplice = ucfirst(strtolower($nome_sottocategoria3 . " " . $nome_brand . " " . $misura));
                        $url_key_semplice = $nome_sottocategoria3 . "-" . $nome_brand . "-" . $sku_semplice;
                    } else {
                        $nome_semplice = ucfirst(strtolower($nome_sottocategoria2 . " " . $nome_brand . " " . $misura));
                        $url_key_semplice = $nome_sottocategoria2 . "-" . $nome_brand . "-" . $sku_semplice;
                    }


                    $array_sku[$countP] = $sku_semplice; //inserisco in un array tutti gli sku inseriti
                    $countP = $countP + 1;

                    $flagImg=false;
                    $inStock=false;
                    for ($k = 0; $k < count($immagini_new); $k++) {
                        if ($immagini_new[$k][1] == "3") {
                            $flagImg=true;
                            break;
                        }
                    }

                    $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                    if (!$productSimple) {
                        $productSimple = Mage::getModel('catalog/product');
                        $productSimple->setSku($sku_semplice);
                        $productSimple->setName($nome_semplice);
                        $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setPrice($prezzo);
                        $productSimple->setTypeId('simple');
                        $productSimple->setAttributeSetId(4);
                        $productSimple->setCategoryIds($array_cat_new);
                        $productSimple->setWeight(1);
                        $productSimple->setTaxClassId(2);
                        $productSimple->setVisibility(1);
                        $productSimple->setStatus(1);
                        $stockData = $productSimple->getStockData();
                        $stockData['qty'] = 0;
                        $stockData['is_in_stock'] = 0;
                        $productSimple->setStockData($stockData);
                        $productSimple->setWebsiteIds(array(1));
                        $productSimple->setUrlKey($url_key_semplice);
                        $productSimple->setData('ca_name', $nome);
                        $productSimple->setData('ca_brand', $id_brandMage);
                        $productSimple->setData('ca_anno', $id_annoMage);
                        $productSimple->setData('ca_stagione', $id_stagioneMage);
                        $productSimple->setData('ca_misura', $id_misuraMage);
                        $productSimple->setData('ca_scalare', $scalare);
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

                            if ($nome_attributo == "Supercolore") {
                                $id_valoreattributo = "";
                                $j=0;
                                foreach ($subattributes as $key => $value) {
                                    if ($j != 0) {
                                        $id_valoreattributo .= "/";
                                    }
                                    $id_valoreattributo = $key;
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

                                    // recupero nome colore
                                    $stringQuery = "select nome_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $nome_colore = $readConnection->fetchOne($stringQuery);

                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_filtraggio_colore";
                                    $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                } else {
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_colore";
                                    $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                    $nome_colore="misto";

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
                                $j=0;

                                if ($nome_attributo=="Composizione" || $nome_attributo=="Made In") {
                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;
                                    }


                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productSimple->setData($nome_attributoMage, $nome_valoreattributo);
                                }
                                else {
                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;

                                        if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                        } else {
                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                            if ($j != 0) {
                                                $stringa_valori .= ",";
                                            }
                                            $stringa_valori .= $id_valoreattributoMage;
                                            $j = $j + 1;
                                        }
                                    }

                                    if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {
                                    } else {
                                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                        $productSimple->setData($nome_attributoMage, $stringa_valori);
                                    }
                                }
                            }

                        }

                        $stringa = "<reference name=\"head\"><action method=\"setRobots\"><value>NOINDEX,NOFOLLOW</value></action></reference>";
                        $productSimple->setData("custom_layout_update", $stringa);
                        $productSimple->save();
                    } else {

                        $attributes = $productSimple->getAttributes();
                        foreach ($attributes as $attribute) {
                            $attributeCode = $attribute->getAttributeCode();
                            if (substr($attributeCode,0,3)=="ca_") {
                                $productSimple->setData($attributeCode, "");
                            }
                        }
                        $productSimple->save();


                        $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                        $productSimple->setName($nome_semplice);
                        $productSimple->setSku($sku_semplice);
                        $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setPrice($prezzo);
                        $productSimple->setCategoryIds($array_cat_new);
                        $productSimple->setUrlKey($url_key_semplice);
                        $productSimple->setData('ca_name', $nome);
                        $productSimple->setData('ca_brand', $id_brandMage);
                        $productSimple->setData('ca_anno', $id_annoMage);
                        $productSimple->setData('ca_stagione', $id_stagioneMage);
                        $productSimple->setData('ca_misura', $id_misuraMage);
                        $productSimple->setData('ca_scalare', $scalare);
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

                            if ($nome_attributo == "Supercolore") {
                                $id_valoreattributo = "";
                                $j=0;
                                foreach ($subattributes as $key => $value) {
                                    if ($j != 0) {
                                        $id_valoreattributo .= "/";
                                    }
                                    $id_valoreattributo = $key;
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

                                    // recupero nome colore
                                    $stringQuery = "select nome_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $nome_colore = $readConnection->fetchOne($stringQuery);

                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_filtraggio_colore";
                                    $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                } else {
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                    $nome_attributoMage = "ca_colore";
                                    $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                    $nome_colore="misto";

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
                                $j=0;
                                if ($nome_attributo=="Composizione" || $nome_attributo=="Made In") {
                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;
                                    }



                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productSimple->setData($nome_attributoMage, $nome_valoreattributo);
                                }
                                else {
                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;

                                        if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                        } else {

                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                            if ($j != 0) {
                                                $stringa_valori .= ",";
                                            }
                                            $stringa_valori .= $id_valoreattributoMage;

                                            $j = $j + 1;
                                        }
                                    }

                                    if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {
                                    } else {
                                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                        $productSimple->setData($nome_attributoMage, $stringa_valori);
                                    }
                                }
                            }

                        }
                        $productSimple->save();
                    }

                }


                // inserimento prodotto configurabile
                $sku_configurabile = $id;
                if ($id_sottocategoria3 != null) {
                    $nome_configurabile = ucfirst(strtolower($nome_sottocategoria3 . " " . $nome_brand));
                    $url_key_configurabile = $nome_sottocategoria3 . "-" . $nome_brand . "-" . $sku_configurabile;
                    $sottoCat=$nome_sottocategoria3;
                } else {
                    $nome_configurabile = ucfirst(strtolower($nome_sottocategoria2 . " " . $nome_brand));
                    $url_key_configurabile = $nome_sottocategoria2 . "-" . $nome_brand . "-" . $sku_configurabile;
                    $sottoCat=$nome_sottocategoria2;
                }


                // meta prodotto configurablile
                if ($id_sottocategoria3 != null) {
                    $numero = rand(0, 2);
                    if ($numero == 0) {
                        $stringa = "Acquista";
                    }
                    if ($numero == 1) {
                        $stringa = "Compra";
                    }
                    if ($numero == 2) {
                        $stringa = "Shop";
                    }

                    $title=ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da ".ucwords(strtolower($nome_categoria))." " . ucwords(strtolower($nome_colore));
                    if ($supercomposizione!="") {
                        $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria3) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " in " . strtolower($supercomposizione) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";
                    }
                    else {
                        $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria3) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";
                    }
                    $keyword1 = $title;
                    $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da ".ucwords(strtolower($nome_categoria));
                    $keyword3 = "Shop online ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da ".ucwords(strtolower($nome_categoria));

                    $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;
                } else {
                    $numero = rand(0, 2);
                    if ($numero == 0) {
                        $stringa = "Acquista";
                    }
                    if ($numero == 1) {
                        $stringa = "Compra";
                    }
                    if ($numero == 2) {
                        $stringa = "Shop";
                    }

                    $title=ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria2)) . " da ".ucwords(strtolower($nome_categoria))." " . ucwords(strtolower($nome_colore));
                    if ($supercomposizione!="") {
                        $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria2) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " in " . strtolower($supercomposizione) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";
                    }
                    else {
                        $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria2) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";
                    }
                    $keyword1 = $title;
                    $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da ".ucwords(strtolower($nome_categoria));
                    $keyword3 = "Shop online ".ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da ".ucwords(strtolower($nome_categoria));

                    $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;
                }


                $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);
                if (!$productConfigurable) {
                    $productConfigurable = Mage::getModel('catalog/product');
                    $productConfigurable->setSku($sku_configurabile);
                    $productConfigurable->setName($nome_configurabile);
                    $productConfigurable->setDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setShortDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setPrice($prezzo);
                    $productConfigurable->setTypeId('configurable');
                    $productConfigurable->setAttributeSetId(4);
                    $productConfigurable->setCategoryIds($array_cat_new);
                    $productConfigurable->setWeight(1);
                    $productConfigurable->setTaxClassId(2);
                    $productConfigurable->setMetaKeyword($keywords);
                    $productConfigurable->setMetaDescription($description);
                    $productConfigurable->setMetaTitle($title);

                    // controllo brand non vendibili. Se non vendibili li metto a non visibile individualmente
                    if (((strtolower($nome_brand)=="adidas x raf simons"  && strtolower($nome_categoria)=="uomo" ) ||
                        strtolower($nome_brand)=="alexander wang" ||
                        strtolower($nome_brand)=="bally" ||
                        (strtolower($nome_brand)=="barba"  && strtolower($nome_categoria)=="uomo" ) ||
                        strtolower($nome_brand)=="burberry-london" ||
                        (strtolower($nome_brand)=="charlotte olympia" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="chiara ferragni" && strtolower($nome_categoria)=="donna" ) ||
                        strtolower($nome_brand)=="dolce & gabbana" ||
                        strtolower($nome_brand)=="drome" ||
                        strtolower($nome_brand)=="dsquared2" ||
                        (strtolower($nome_brand)=="edward achour" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="emanuela caruso" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="ermanno scervino" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="faliero sarti" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="fausto puglisi" && strtolower($nome_categoria)=="donna" ) ||
                        strtolower($nome_brand)=="fendi" ||
                        (strtolower($nome_brand)=="gianluca capannolo" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="gianvito rossi" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="giorgio armani" && strtolower($nome_categoria)=="uomo" ) ||
                        (strtolower($nome_brand)=="emporio armani" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="giorgio brato" && strtolower($nome_categoria)=="uomo" ) ||
                        strtolower($nome_brand)=="zanotti"  ||
                        strtolower($nome_brand)=="golden goose"  ||
                        strtolower($nome_brand)=="haider ackermann"  ||
                        (strtolower($nome_brand)=="jil sander" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="jimmy choo" && strtolower($nome_categoria)=="donna" ) ||
                        strtolower($nome_brand)=="kenzo"  ||
                        strtolower($nome_brand)=="lanvin"  ||
                        (strtolower($nome_brand)=="marcelo burlon" && strtolower($nome_categoria)=="uomo" ) ||
                        (strtolower($nome_brand)=="michael michael kors" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="missoni" && strtolower($nome_categoria)=="donna" ) ||
                        strtolower($nome_brand)=="moncler basic" ||
                        strtolower($nome_brand)=="moncler gamme rouge/bleu" ||
                        strtolower($nome_brand)=="mr & mrs italy" ||
                        strtolower($nome_brand)=="msgm" ||
                        (strtolower($nome_brand)=="peter pilotto" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="pierre louis mascia" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="pollini by nicholas kirkwood" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="proenza schouler" && strtolower($nome_categoria)=="donna" ) ||
                        strtolower($nome_brand)=="rick owens" ||
                        strtolower($nome_brand)=="sacai" ||
                        strtolower($nome_brand)=="saint laurent" ||
                        strtolower($nome_brand)=="salvatore ferragamo" ||
                        (strtolower($nome_brand)=="stella mccartney" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="stuart weitzman" && strtolower($nome_categoria)=="donna" ) ||
                        (strtolower($nome_brand)=="tagliatore" && strtolower($nome_categoria)=="uomo" ) ||
                        (strtolower($nome_brand)=="claudio tonello" && strtolower($nome_categoria)=="uomo" ) ||
                        strtolower($nome_brand)=="valentino")&&
                        ($id!="151405ABS000006-F0V1Z" &&
                            $id!="151405ABS000006-F0KUR" &&
                            $id!="151405ABS000006-F0V20" &&
                            $id!="151405ABS000006-F0V21" &&
                            $id!="151405ABS000007-F0V2N" &&
                            $id!="151405ABS000007-F0KUR" &&
                            $id!="151405ABS000007-F0V2M" &&
                            $id!="151405ABS000007-F0L92" &&
                            $id!="151405ABS000008-F0V1W" &&
                            $id!="151405ABS000008-F0V1X" &&
                            $id!="151405ABS000009-F0V8G" &&
                            $id!="151405ABS000054-F0DVU" &&
                            $id!="151405ABS000054-F0Z29" &&
                            $id!="151405ABS000055-F0Y7W" &&
                            $id!="151405ABS000056-F018C" &&
                            $id!="151405ABS000056-F018B" &&
                            $id!="151405ABS000057-F0GN2" &&
                            $id!="151405ABS000057-F0F89" &&
                            $id!="151405ABS000057-F0U52" &&
                            $id!="151405ABS000057-F0W6Q" &&
                            $id!="151405ABS000057-F0L17" &&
                            $id!="151405ABS000057-F0V1A" &&
                            $id!="151405ABS000057-F0A22" &&
                            $id!="151405ABS000057-F0KUR" &&
                            $id!="151405ABS000057-F0M8A")

                    )
                    {
                        $productConfigurable->setVisibility(4);
                    }
                    else {
                        $productConfigurable->setVisibility(1);
                    }

                    $productConfigurable->setStatus(1);
                    $stockData = $productConfigurable->getStockData();
                    $stockData['qty'] = 0;
                    $stockData['is_in_stock'] = 0;
                    $productConfigurable->setStockData($stockData);
                    $productConfigurable->setWebsiteIds(array(1));
                    $productConfigurable->setUrlKey($url_key_configurabile);

                    //inserimento immagini
                    for ($k = 0; $k < count($immagini_new); $k++) {
                        $image_location = getDownloadImage("product", $immagini_new[$k][0],$sottoCat,$nome_brand,$nome_colore,$id);
                        if ($image_location!="") {
                            if ($immagini_new[$k][1] == "3") {
                                $productConfigurable->addImageToMediaGallery($image_location, array('image', 'small_image', 'thumbnail'), false, false);

                            } else if ($immagini_new[$k][1] == "1") {


                            } else if ($immagini_new[$k][1] == "2") {


                            } else {
                                $productConfigurable->addImageToMediaGallery($image_location, array(""), false, false);

                            }

                        }
                    }

                    $productConfigurable->setData('ca_name', $nome);
                    $productConfigurable->setData('ca_brand', $id_brandMage);
                    $productConfigurable->setData('ca_anno', $id_annoMage);
                    $productConfigurable->setData('ca_stagione', $id_stagioneMage);
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

                        if ($nome_attributo == "Supercolore") {
                            $id_valoreattributo = "";
                            $j=0;
                            foreach ($subattributes as $key => $value) {
                                if ($j != 0) {
                                    $id_valoreattributo .= "/";
                                }
                                $id_valoreattributo = $key;
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
                            $j=0;
                            if ($nome_attributo=="Composizione" || $nome_attributo=="Made In") {
                                foreach ($subattributes as $key => $value) {

                                    $id_valoreattributo = $key;
                                    $nome_valoreattributo = $value;
                                }


                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                $productConfigurable->setData($nome_attributoMage, $nome_valoreattributo);
                            }
                            else {
                                foreach ($subattributes as $key => $value) {

                                    $id_valoreattributo = $key;
                                    $nome_valoreattributo = $value;

                                    if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                    } else {

                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                        if ($j != 0) {
                                            $stringa_valori .= ",";
                                        }
                                        $stringa_valori .= $id_valoreattributoMage;
                                        $j = $j + 1;
                                    }
                                }

                                if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                } else {
                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productConfigurable->setData($nome_attributoMage, $stringa_valori);
                                }
                            }
                        }
                    }
                    Mage::helper('bubble_api/catalog_product')->associateProducts($productConfigurable, $array_sku, array(), array());

                    try {
                        $productConfigurable->save();

                    } catch (Exception $e) {
                        Mage::log($e);
                    }
                    Mage::log("FATTO");

                } else {

                    // eliminare immagini
                    $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                    $items = $mediaApi->items($productConfigurable->getId());
                    foreach($items as $item) {
                        $mediaApi->remove($productConfigurable->getId(), $item['file']);
                        unlink(Mage::getBaseDir('media') . '/catalog/product' . $item['file']);
                    }
                    $attributes = $productConfigurable->getAttributes();
                    foreach ($attributes as $attribute) {
                        $attributeCode = $attribute->getAttributeCode();
                        if (substr($attributeCode,0,3)=="ca_") {
                            $productConfigurable->setData($attributeCode, "");
                        }
                    }


                    $productConfigurable->save();


                    $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);
                    $productConfigurable->setSku($sku_configurabile);
                    $productConfigurable->setName($nome_configurabile);
                    $productConfigurable->setDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setShortDescription(ucfirst(strtolower($descrizione)));
                    $productConfigurable->setPrice($prezzo);
                    $productConfigurable->setCategoryIds($array_cat_new);
                    $productConfigurable->setUrlKey($url_key_configurabile);
                    $productConfigurable->setMetaKeyword($keywords);
                    $productConfigurable->setMetaDescription($description);
                    $productConfigurable->setMetaTitle($title);

                    // controllo brand non vendibili. Se non vendibili li metto a non visibile individualmente
                    if (((strtolower($nome_brand)=="adidas x raf simons"  && strtolower($nome_categoria)=="uomo" ) ||
                            strtolower($nome_brand)=="alexander wang" ||
                            strtolower($nome_brand)=="bally" ||
                            (strtolower($nome_brand)=="barba"  && strtolower($nome_categoria)=="uomo" ) ||
                            strtolower($nome_brand)=="burberry-london" ||
                            (strtolower($nome_brand)=="charlotte olympia" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="chiara ferragni" && strtolower($nome_categoria)=="donna" ) ||
                            strtolower($nome_brand)=="dolce & gabbana" ||
                            strtolower($nome_brand)=="drome" ||
                            strtolower($nome_brand)=="dsquared2" ||
                            (strtolower($nome_brand)=="edward achour" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="emanuela caruso" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="ermanno scervino" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="faliero sarti" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="fausto puglisi" && strtolower($nome_categoria)=="donna" ) ||
                            strtolower($nome_brand)=="fendi" ||
                            (strtolower($nome_brand)=="gianluca capannolo" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="gianvito rossi" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="giorgio armani" && strtolower($nome_categoria)=="uomo" ) ||
                            (strtolower($nome_brand)=="emporio armani" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="giorgio brato" && strtolower($nome_categoria)=="uomo" ) ||
                            strtolower($nome_brand)=="giuseppe zanotti"  ||
                            strtolower($nome_brand)=="golden goose"  ||
                            strtolower($nome_brand)=="haider ackermann"  ||
                            (strtolower($nome_brand)=="jil sander" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="jimmy choo" && strtolower($nome_categoria)=="donna" ) ||
                            strtolower($nome_brand)=="kenzo"  ||
                            strtolower($nome_brand)=="lanvin"  ||
                            (strtolower($nome_brand)=="marcelo burlon" && strtolower($nome_categoria)=="uomo" ) ||
                            (strtolower($nome_brand)=="michael michael kors" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="missoni" && strtolower($nome_categoria)=="donna" ) ||
                            strtolower($nome_brand)=="moncler basic" ||
                            strtolower($nome_brand)=="moncler gamme rouge/bleu" ||
                            strtolower($nome_brand)=="mr & mrs italy" ||
                            strtolower($nome_brand)=="msgm" ||
                            (strtolower($nome_brand)=="peter pilotto" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="pierre louis mascia" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="pollini by nicholas kirkwood" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="proenza schouler" && strtolower($nome_categoria)=="donna" ) ||
                            strtolower($nome_brand)=="rick owens" ||
                            strtolower($nome_brand)=="sacai" ||
                            strtolower($nome_brand)=="saint laurent" ||
                            strtolower($nome_brand)=="salvatore ferragamo" ||
                            (strtolower($nome_brand)=="stella mccartney" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="stuart weitzman" && strtolower($nome_categoria)=="donna" ) ||
                            (strtolower($nome_brand)=="tagliatore" && strtolower($nome_categoria)=="uomo" ) ||
                            (strtolower($nome_brand)=="claudio tonello" && strtolower($nome_categoria)=="uomo" ) ||
                            strtolower($nome_brand)=="valentino")&&
                        ($id!="151405ABS000006-F0V1Z" &&
                            $id!="151405ABS000006-F0KUR" &&
                            $id!="151405ABS000006-F0V20" &&
                            $id!="151405ABS000006-F0V21" &&
                            $id!="151405ABS000007-F0V2N" &&
                            $id!="151405ABS000007-F0KUR" &&
                            $id!="151405ABS000007-F0V2M" &&
                            $id!="151405ABS000007-F0L92" &&
                            $id!="151405ABS000008-F0V1W" &&
                            $id!="151405ABS000008-F0V1X" &&
                            $id!="151405ABS000009-F0V8G" &&
                            $id!="151405ABS000054-F0DVU" &&
                            $id!="151405ABS000054-F0Z29" &&
                            $id!="151405ABS000055-F0Y7W" &&
                            $id!="151405ABS000056-F018C" &&
                            $id!="151405ABS000056-F018B" &&
                            $id!="151405ABS000057-F0GN2" &&
                            $id!="151405ABS000057-F0F89" &&
                            $id!="151405ABS000057-F0U52" &&
                            $id!="151405ABS000057-F0W6Q" &&
                            $id!="151405ABS000057-F0L17" &&
                            $id!="151405ABS000057-F0V1A" &&
                            $id!="151405ABS000057-F0A22" &&
                            $id!="151405ABS000057-F0KUR" &&
                            $id!="151405ABS000057-F0M8A")

                    )
                    {
                        $productConfigurable->setVisibility(4);
                    }
                    else {
                        $productConfigurable->setVisibility(1);
                    }


                    //inserimento immagini
                    for ($k = 0; $k < count($immagini_new); $k++) {
                        $image_location = getDownloadImage("product", $immagini_new[$k][0],$sottoCat,$nome_brand,$nome_colore,$id);
                        if ($image_location!="") {
                            if ($immagini_new[$k][1] == "3") {
                                $productConfigurable->addImageToMediaGallery($image_location, array('image', 'small_image', 'thumbnail'), false, false);

                            } else if ($immagini_new[$k][1] == "1") {


                            } else if ($immagini_new[$k][1] == "2") {

                            } else {
                                $productConfigurable->addImageToMediaGallery($image_location, array(""), false, false);

                            }
                        }
                    }

                    $productConfigurable->setData('ca_name', $nome);
                    $productConfigurable->setData('ca_brand', $id_brandMage);
                    $productConfigurable->setData('ca_anno', $id_annoMage);
                    $productConfigurable->setData('ca_stagione', $id_stagioneMage);
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
                            $j=0;
                            if ($nome_attributo=="Composizione" || $nome_attributo=="Made In") {
                                foreach ($subattributes as $key => $value) {

                                    $id_valoreattributo = $key;
                                    $nome_valoreattributo = $value;
                                }


                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                $productConfigurable->setData($nome_attributoMage, $nome_valoreattributo);
                            }
                            else {
                                foreach ($subattributes as $key => $value) {

                                    $id_valoreattributo = $key;
                                    $nome_valoreattributo = $value;

                                    if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                    } else {

                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                        if ($j != 0) {
                                            $stringa_valori .= ",";
                                        }
                                        $stringa_valori .= $id_valoreattributoMage;
                                        $j = $j + 1;
                                    }
                                }

                                if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                } else {
                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $productConfigurable->setData($nome_attributoMage, $stringa_valori);
                                }
                            }
                        }
                    }

                    Mage::log("F");


                    //Mage::helper('bubble_api/catalog_product')->associateProducts($productConfigurable, $array_sku, array(), array());



                    try {
                        $productConfigurable->save();

                    } catch (Exception $e) {
                        Mage::log($e->getMessage());
                    }



                }


                // aggiornamento immagini
                $product = Mage::getModel('catalog/product');
                $product->load($product->getIdBySku($sku_configurabile));
                $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
                $gallery = $attributes['media_gallery'];
                $images = $product->getMediaGalleryImages();
                foreach ($images as $image) {
                    $path = $image->getUrl();
                    $file = basename($path);

                    $punto = strrpos($file, ".");
                    $file_new = substr($file, 0, $punto);

                    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                    $numero_img = substr($file_new, strlen($file_new) - 1, 1);


                    if ($numero_img == "3") {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $product,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => "1")
                        );

                        $product->getResource()->saveAttribute($product, 'media_gallery');
                        $product->save();
                    } else {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $product,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => ($numero_img - 2))
                        );

                        $product->getResource()->saveAttribute($product, 'media_gallery');
                        $product->save();
                    }

                }

                $countP = 0;
                $array_sku = array("");




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



