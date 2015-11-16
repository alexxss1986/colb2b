<?php
function nomeValoreAttributo($id_attributo,$valore_opzione){
    $int_attr_id = $id_attributo; // or any given id.
    $int_attr_value = $valore_opzione; // or any given attribute value id.

    $attr = Mage::getModel('catalog/resource_eav_attribute')->load($int_attr_id);

    $value="";
    if ($attr->usesSource()) {
        $value = $attr->getSource()->getOptionText($int_attr_value);
    }

    return $value;
}

function getLastInsertId($tableName, $primaryKey)
{
    //SELECT MAX(id) FROM table
    $db = Mage::getModel('core/resource')->getConnection('core_read');
    $result = $db->raw_fetchRow("SELECT MAX(`{$primaryKey}`) as LastID FROM `{$tableName}`");
    return $result['LastID'];
}

session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['type_id']) && isset($_REQUEST['id_prodotto'])){
            $type_id=$_REQUEST['type_id'];
            $id_prodotto=$_REQUEST['id_prodotto'];

            include("percorsoMage.php");
            require_once "../".$MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


            include("iva.php");


            if (isset($_REQUEST['nome']) &&
                isset($_REQUEST['descrizione']) &&
                isset($_REQUEST['sku']) &&
                isset($_REQUEST['brand']) &&
                isset($_REQUEST['stagione']) &&
                isset($_REQUEST['anno']) &&
                isset($_REQUEST['prezzo']) &&
                isset($_REQUEST['categoria']) &&
                //isset($_FILES['immagini']) &&
                isset($_REQUEST['sottocategoria1']) &&
                isset($_REQUEST['sottocategoria2']) &&
                isset($_SESSION['qta']) &&
                isset($_SESSION['taglie_s']) &&
                isset($_SESSION['scalari_s']))
            {

                $resource = Mage::getSingleton('core/resource');
                $readConnection = $resource->getConnection('core_read');
                $writeConnection = $resource->getConnection('core_write');

                $nome=$_REQUEST['nome'];
                $descrizione=$_REQUEST['descrizione'];
                $sku=$_REQUEST['sku'];
                $codice_colore=$_REQUEST['codice_colore'];
                $codice_produttore=$_REQUEST['codice_produttore'];
                $id_categoria=$_REQUEST['categoria'];
                $id_sottocategoria1=$_REQUEST['sottocategoria1'];
                $brand=$_REQUEST['brand'];
                $stagione=$_REQUEST['stagione'];
                $anno=$_REQUEST['anno'];
                $prezzo=$_REQUEST['prezzo'];
                /*$immagini = $_FILES['immagini']['tmp_name'];
                $immagini_nome = $_FILES['immagini']['name'];*/

                $motivo=array("");
                if (isset($_REQUEST['motivo'])){
                    $motivo=$_REQUEST['motivo'];
                }

                $supercomposizione=array("");
                if (isset($_REQUEST['supercomposizione'])){
                    $supercomposizione=$_REQUEST['supercomposizione'];
                }

                $made_in=$_REQUEST['made_in'];
                $composizione=$_REQUEST['composizione'];

                $supercolore=array("");
                if (isset($_REQUEST['supercolore'])){
                    $supercolore=$_REQUEST['supercolore'];
                }

                $tipborsadonna=array("");
                if (isset($_REQUEST['tipborsadonna'])){
                    $tipborsadonna=$_REQUEST['tipborsadonna'];
                }

                $tipborsauomo=array("");
                if (isset($_REQUEST['tipborsauomo'])){
                    $tipborsauomo=$_REQUEST['tipborsauomo'];
                }

                $dimensioni_borsa_lunghezza=array("");
                if (isset($_REQUEST['dimensioni_borsa_lunghezza'])){
                    $dimensioni_borsa_lunghezza=$_REQUEST['dimensioni_borsa_lunghezza'];
                }

                $dimensioni_borsa_altezza="";
                if (isset($_REQUEST['dimensioni_borsa_altezza'])){
                    $dimensioni_borsa_altezza=$_REQUEST['dimensioni_borsa_altezza'];
                }

                $dimensioni_borsa_profondita="";
                if (isset($_REQUEST['dimensioni_borsa_profondita'])){
                    $dimensioni_borsa_profondita=$_REQUEST['dimensioni_borsa_profondita'];
                }

                $dimensioni_borsa_altezza_manico="";
                if (isset($_REQUEST['dimensioni_borsa_altezza_manico'])){
                    $dimensioni_borsa_altezza_manico=$_REQUEST['dimensioni_borsa_altezza_manico'];
                }

                $dimensioni_borsa_lunghezza_tracolla="";
                if (isset($_REQUEST['dimensioni_borsa_lunghezza_tracolla'])){
                    $dimensioni_borsa_lunghezza_tracolla=$_REQUEST['dimensioni_borsa_lunghezza_tracolla'];
                }

                $tipaccessoridonna=array("");
                if (isset($_REQUEST['tipaccessoridonna'])){
                    $tipaccessoridonna=$_REQUEST['tipaccessoridonna'];
                }

                $tipaccessoriuomo=array("");
                if (isset($_REQUEST['tipaccessoriuomo'])){
                    $tipaccessoriuomo=$_REQUEST['tipaccessoriuomo'];
                }

                $cintura_lunghezza="";
                if (isset($_REQUEST['cintura_lunghezza'])){
                    $cintura_lunghezza=$_REQUEST['cintura_lunghezza'];
                }

                $cintura_altezza="";
                if (isset($_REQUEST['cintura_altezza'])){
                    $cintura_altezza=$_REQUEST['cintura_altezza'];
                }

                $dimensioni_accessorio_lunghezza="";
                if (isset($_REQUEST['dimensioni_accessorio_lunghezza'])){
                    $dimensioni_accessorio_lunghezza=$_REQUEST['dimensioni_accessorio_lunghezza'];
                }

                $dimensioni_accessorio_altezza="";
                if (isset($_REQUEST['dimensioni_accessorio_altezza'])){
                    $dimensioni_accessorio_altezza=$_REQUEST['dimensioni_accessorio_altezza'];
                }

                $dimensioni_accessorio_profondita="";
                if (isset($_REQUEST['dimensioni_accessorio_profondita'])){
                    $dimensioni_accessorio_profondita=$_REQUEST['dimensioni_accessorio_profondita'];
                }

                $tipcalzdonna=array("");
                if (isset($_REQUEST['tipcalzdonna'])){
                    $tipcalzdonna=$_REQUEST['tipcalzdonna'];
                }

                $tipcalzuomo=array("");
                if (isset($_REQUEST['tipcalzuomo'])){
                    $tipcalzuomo=$_REQUEST['tipcalzuomo'];
                }

                $dimensioni_calzatura_altezza_tacco="";
                if (isset($_REQUEST['dimensioni_calzatura_altezza_tacco'])){
                    $dimensioni_calzatura_altezza_tacco=$_REQUEST['dimensioni_calzatura_altezza_tacco'];
                }

                $dimensioni_calzatura_altezza_plateau="";
                if (isset($_REQUEST['dimensioni_calzatura_altezza_plateau'])){
                    $dimensioni_calzatura_altezza_plateau=$_REQUEST['dimensioni_calzatura_altezza_plateau'];
                }

                $dimensioni_calzatura_lunghezza_soletta="";
                if (isset($_REQUEST['dimensioni_calzatura_lunghezza_soletta'])){
                    $dimensioni_calzatura_lunghezza_soletta=$_REQUEST['dimensioni_calzatura_lunghezza_soletta'];
                }

                $tipotaccodonna=array("");
                if (isset($_REQUEST['tipotaccodonna'])){
                    $tipotaccodonna=$_REQUEST['tipotaccodonna'];
                }

                $tiposuola=array("");
                if (isset($tiposuola['tiposuola'])){
                    $tiposuola=$_REQUEST['tiposuola'];
                }

                $tipopuntadonna=array("");
                if (isset($_REQUEST['tipopuntadonna'])){
                    $tipopuntadonna=$_REQUEST['tipopuntadonna'];
                }

                $tipopuntauomo=array("");
                if (isset($_REQUEST['tipopuntauomo'])){
                    $tipopuntauomo=$_REQUEST['tipopuntauomo'];
                }

                $vestibilitaabiti=array("");
                if (isset($_REQUEST['vestibilitaabiti'])){
                    $vestibilitaabiti=$_REQUEST['vestibilitaabiti'];
                }

                $vestibilitatopwear=array("");
                if (isset($_REQUEST['vestibilitatopwear'])){
                    $vestibilitatopwear=$_REQUEST['vestibilitatopwear'];
                }

                $vestibilitagonne=array("");
                if (isset($_REQUEST['vestibilitagonne'])){
                    $vestibilitagonne=$_REQUEST['vestibilitagonne'];
                }

                $vestibilitapantaloni=array("");
                if (isset($_REQUEST['vestibilitapantaloni'])){
                    $vestibilitapantaloni=$_REQUEST['vestibilitapantaloni'];
                }

                $vestcamicieuomo=array("");
                if (isset($_REQUEST['vestcamicieuomo'])){
                    $vestcamicieuomo=$_REQUEST['vestcamicieuomo'];
                }

                $vestcamiciedonna=array("");
                if (isset($_REQUEST['vestcamiciedonna'])){
                    $vestcamiciedonna=$_REQUEST['vestcamiciedonna'];
                }

                $vestibilitagiacche=array("");
                if (isset($_REQUEST['vestibilitagiacche'])){
                    $vestibilitagiacche=$_REQUEST['vestibilitagiacche'];
                }

                $vestibilitacapispalla=array("");
                if (isset($_REQUEST['vestibilitacapispalla'])){
                    $vestibilitacapispalla=$_REQUEST['vestibilitacapispalla'];
                }


                $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                foreach ($pCollection as $process) {
                    $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
                }

                $prezzo = ($prezzo * 100) / ($iva + 100);


                // conversione attributi custom
                $stringaIdAttributo = "";
                $stringaValoreAttributo ="";
                $j = 0;
                foreach ($supercolore as $row) {

                    $id_valoreattributoMage = $row;
                    $nome_valoreattributo = nomeValoreAttributo(305, $id_valoreattributoMage);

                    $stringQuery = "select id_ws from " . $resource->getTableName('wsca_colore') . " where id_magento='" . $id_valoreattributoMage . "'";
                    $id_valoreattributo = $readConnection->fetchOne($stringQuery);

                    if ($j != 0) {
                        $stringaValoreAttributo .= "/";
                        $stringaIdAttributo .= "/";
                    }
                    $stringaValoreAttributo .= $nome_valoreattributo;
                    $stringaIdAttributo .= $id_valoreattributo;

                    $j = $j + 1;

                }

                $id_attributo = "000001";




                // controllo esistenza opzione in magento per l'attributo in questione
                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $stringaIdAttributo . "' and id_attributes='" . $id_attributo . "'";
                $idSuperColore = $readConnection->fetchOne($stringQuery);
                if ($idSuperColore == null) {

                    $attribute_model = Mage::getModel('eav/entity_attribute');
                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000001");
                    $attribute = $attribute_model->load($attribute_code);

                    $attribute->setData('option', array(
                        'value' => array(
                            'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                        )
                    ));
                    $attribute->save();

                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                    $optionTable = $setup->getTable('eav/attribute_option');

                    $idSuperColore = getLastInsertId($optionTable, 'option_id');



                    $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $idSuperColore . "','" . $stringaIdAttributo . "','" . $id_attributo . "')";
                    $writeConnection->query($query);

                }



                if (count($supercolore)== 1) {

                    // recupero il nome della stagione
                    $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
                    $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_colore");
                    $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($attributeModel->getId())
                        ->setStoreFilter(3)
                        ->load();

                    foreach ($_collection->toOptionArray() as $option) {
                        if ($option['value'] == $id_valoreattributoMage) {
                            $nome_colore = $option['label'];
                            break;
                        }
                    }



                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                    $filtraggioColore= $readConnection->fetchOne($stringQuery);

                    $colore=$id_valoreattributoMage;


                } else {
                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                    $colore=$id_valoreattributoMage;

                    $nome_colore = $codice_colore;


                    $filtraggioColoriArray = explode("/", $stringaIdAttributo);
                    $filtraggioColore = "";
                    for ($u = 0; $u < count($filtraggioColoriArray); $u++) {
                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggioColoriArray[$u] . "'";
                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                        if ($u != 0) {
                            $filtraggioColore .= ",";
                        }
                        $filtraggioColore .= $id_valoreattributoMage;

                    }


                }



                $stringaSupercolore = "";
                $j = 0;
                foreach ($supercolore as $row) {
                    if ($j != 0) {
                        $stringaSupercolore .= ",";
                    }
                    $stringaSupercolore .= $row;
                    $j = $j + 1;
                }


                $stringaMotivo = "";
                $j = 0;
                foreach ($motivo as $row) {
                    if ($j != 0) {
                        $stringaMotivo .= ",";
                    }
                    $stringaMotivo .= $row;
                    $j = $j + 1;
                }


                $stringaSupercomposizione = "";
                $j = 0;
                foreach ($supercomposizione as $row) {
                    if ($j != 0) {
                        $stringaSupercomposizione .= ",";
                    }
                    $stringaSupercomposizione .= $row;
                    $j = $j + 1;
                }


                $stringaTipborsadonna = "";
                $j = 0;
                foreach ($tipborsadonna as $row) {
                    if ($j != 0) {
                        $stringaTipborsadonna .= ",";
                    }
                    $stringaTipborsadonna .= $row;
                    $j = $j + 1;
                }


                $stringaTipborsauomo = "";
                $j = 0;
                foreach ($tipborsauomo as $row) {
                    if ($j != 0) {
                        $stringaTipborsauomo .= ",";
                    }
                    $stringaTipborsauomo .= $row;
                    $j = $j + 1;
                }

                $stringaTipaccessoridonna = "";
                $j = 0;
                foreach ($tipaccessoridonna as $row) {
                    if ($j != 0) {
                        $stringaTipaccessoridonna .= ",";
                    }
                    $stringaTipaccessoridonna .= $row;
                    $j = $j + 1;
                }

                $stringaTipaccessoriuomo = "";
                $j = 0;
                foreach ($tipaccessoriuomo as $row) {
                    if ($j != 0) {
                        $stringaTipaccessoriuomo .= ",";
                    }
                    $stringaTipaccessoriuomo .= $row;
                    $j = $j + 1;
                }

                $stringaTipcalzdonna = "";
                $j = 0;
                foreach ($tipcalzdonna as $row) {
                    if ($j != 0) {
                        $stringaTipcalzdonna .= ",";
                    }
                    $stringaTipcalzdonna .= $row;
                    $j = $j + 1;
                }

                $stringaTipcalzuomo = "";
                $j = 0;
                foreach ($tipcalzuomo as $row) {
                    if ($j != 0) {
                        $stringaTipcalzuomo .= ",";
                    }
                    $stringaTipcalzuomo .= $row;
                    $j = $j + 1;
                }

                $stringaTipotaccodonna = "";
                $j = 0;
                foreach ($tipotaccodonna as $row) {
                    if ($j != 0) {
                        $stringaTipotaccodonna .= ",";
                    }
                    $stringaTipotaccodonna .= $row;
                    $j = $j + 1;
                }

                $stringaTiposuola = "";
                $j = 0;
                foreach ($tiposuola as $row) {
                    if ($j != 0) {
                        $stringaTiposuola .= ",";
                    }
                    $stringaTiposuola .= $row;
                    $j = $j + 1;
                }

                $stringaTipopuntadonna = "";
                $j = 0;
                foreach ($tipopuntadonna as $row) {
                    if ($j != 0) {
                        $stringaTipopuntadonna .= ",";
                    }
                    $stringaTipopuntadonna .= $row;
                    $j = $j + 1;
                }

                $stringaTipopuntauomo = "";
                $j = 0;
                foreach ($tipopuntauomo as $row) {
                    if ($j != 0) {
                        $stringaTipopuntauomo .= ",";
                    }
                    $stringaTipopuntauomo .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitaabiti = "";
                $j = 0;
                foreach ($vestibilitaabiti as $row) {
                    if ($j != 0) {
                        $stringaVestibilitaabiti .= ",";
                    }
                    $stringaVestibilitaabiti .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitatopwear = "";
                $j = 0;
                foreach ($vestibilitatopwear as $row) {
                    if ($j != 0) {
                        $stringaVestibilitatopwear .= ",";
                    }
                    $stringaVestibilitatopwear .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitagonne = "";
                $j = 0;
                foreach ($vestibilitagonne as $row) {
                    if ($j != 0) {
                        $stringaVestibilitagonne .= ",";
                    }
                    $stringaVestibilitagonne .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitagiacche = "";
                $j = 0;
                foreach ($vestibilitagiacche as $row) {
                    if ($j != 0) {
                        $stringaVestibilitagiacche .= ",";
                    }
                    $stringaVestibilitagiacche .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitacapispalla = "";
                $j = 0;
                foreach ($vestibilitacapispalla as $row) {
                    if ($j != 0) {
                        $stringaVestibilitacapispalla .= ",";
                    }
                    $stringaVestibilitacapispalla .= $row;
                    $j = $j + 1;
                }

                $stringaVestibilitapantaloni = "";
                $j = 0;
                foreach ($vestibilitapantaloni as $row) {
                    if ($j != 0) {
                        $stringaVestibilitapantaloni .= ",";
                    }
                    $stringaVestibilitapantaloni .= $row;
                    $j = $j + 1;
                }

                $stringaVestcamiciedonna = "";
                $j = 0;
                foreach ($vestcamiciedonna as $row) {
                    if ($j != 0) {
                        $stringaVestcamiciedonna .= ",";
                    }
                    $stringaVestcamiciedonna .= $row;
                    $j = $j + 1;
                }

                $stringaVestcamicieuomo = "";
                $j = 0;
                foreach ($vestcamicieuomo as $row) {
                    if ($j != 0) {
                        $stringaVestcamicieuomo .= ",";
                    }
                    $stringaVestcamicieuomo .= $row;
                    $j = $j + 1;
                }




                $k = 0;




                    // prodotto configurabile non esistente
                    $l = 0;
                    $cat[$l] = 2;
                    $l = $l + 1;
                    $cat[$l] = $id_categoria;
                    $l = $l + 1;
                    $cat[$l] = $id_sottocategoria1;
                    $l = $l + 1;

                    $id_sottoC = "sottocategoria2";
                    while (isset($_REQUEST[$id_sottoC])) {
                        $id_sc = $_REQUEST[$id_sottoC];
                        $cat[$l] = $id_sc;

                        $id_sottoC = "sottocategoria" . $l;


                        $l = $l + 1;

                    }


                    $nome_brand = nomeValoreAttributo(134, $brand);


                    // recupero il nome della stagione
                    $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
                    $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_stagione");
                    $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                        ->setAttributeFilter($attributeModel->getId())
                        ->setStoreFilter(3)
                        ->load();

                    foreach ($_collection->toOptionArray() as $option) {
                        if ($option['value'] == $stagione) {
                            $nome_stagione = $option['label'];
                            break;
                        }
                    }





                    // recupero l'ultima categoria e la prima categoria
                    $ultimo = count($cat) - 1;
                    $lastCategory = $cat[$ultimo];
                    while ($lastCategory == "") {
                        $ultimo = $ultimo - 1;
                        $lastCategory = $cat[$ultimo];
                    }
                    $firstCategory = $cat[1];

                    $category = Mage::getModel('catalog/category')->setStoreId(3)->load($firstCategory);
                    $firstCategortyDesc = $category->getName();

                    $category = Mage::getModel('catalog/category')->setStoreId(3)->load($lastCategory);
                    $lastCategortyDesc = $category->getName();


                    $stringa = "Shop";
                    $nome_configurabile = ucfirst(strtolower($lastCategortyDesc . " " . $nome_brand));


                    $title = ucwords(strtolower($firstCategortyDesc)) . " " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($lastCategortyDesc)) . " " . ucwords(strtolower($nome_colore));
                    $description = $stringa . " online on coltortiboutique.com: " . ucwords(strtolower($firstCategortyDesc)) . " " . ucwords(strtolower($nome_brand)) . " " . strtolower($lastCategortyDesc) . " " . strtolower($nome_colore) . " of " . strtolower($nome_stagione) . ". Guaranteed express delivery and returns";

                    $keyword1 = $title;
                    $keyword2 = ucwords(strtolower($firstCategortyDesc)) . " " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($lastCategortyDesc));
                    $keyword3 = "Shop online " . ucwords(strtolower($firstCategortyDesc)) . " " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($lastCategortyDesc));

                    $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;

                    $url_key = $lastCategortyDesc . "-" . $nome_brand . "-" . $sku;


                    $flag = true;

                    $id_sottocategoria2 = $cat[3];
                    $id_sottocategoria3 = "";
                    if (isset($cat[4])) {
                        $id_sottocategoria3 = $cat[4];
                    }



                $productConfigurable = Mage::getModel('catalog/product')->load($id_prodotto);
                $productConfigurable->setName($nome_configurabile);
                $productConfigurable->setDescription(ucfirst(strtolower($descrizione)));
                $productConfigurable->setShortDescription(ucfirst(strtolower($descrizione)));
                $productConfigurable->setPrice($prezzo);
                $productConfigurable->setCategoryIds($cat);
                $productConfigurable->setMetaKeyword($keywords);
                $productConfigurable->setMetaDescription($description);
                $productConfigurable->setMetaTitle($title);


                if (($brand == 2352 ||
                        $brand == 1069 ||
                        $brand == 1083 ||
                        $brand == 982 ||
                        $brand == 1094 ||
                        $brand == 2369 ||
                        $brand == 933 ||
                        $brand == 975 ||
                        $brand == 2548 ||
                        $brand == 948 ||
                        $brand == 2348 ||
                        $brand == 2509 ||
                        $brand == 2474 ||
                        $brand == 985 ||
                        $brand == 1084 ||
                        $brand == 993 ||
                        $brand == 1070 ||
                        $brand == 945 ||
                        $brand == 2481 ||
                        $brand == 939 ||
                        $brand == 1080 ||
                        $brand == 955 ||
                        $brand == 1030 ||
                        $brand == 1085 ||
                        $brand == 958 ||
                        $brand == 1078 ||
                        $brand == 1079 ||
                        $brand == 949 ||
                        $brand == 947 ||
                        $brand == 951 ||
                        $brand == 2350 ||
                        $brand == 984 ||
                        $brand == 959 ||
                        $brand == 1019 ||
                        $brand == 981 ||
                        $brand == 1087 ||
                        $brand == 1086 ||
                        $brand == 1018 ||
                        $brand == 943 ||
                        $brand == 972 ||
                        $brand == 1073 ||
                        $brand == 1075 ||
                        $brand == 998 ||
                        $brand == 2351 ||
                        $brand == 935 ||
                        $brand == 2518 ||
                        $brand == 950 ||
                        $brand == 1077 ||
                        $brand == 971 ||
                        $brand == 2363 ||
                        $brand == 1089 ||
                        $brand == 1038 ||
                        $brand == 1036 ||
                        $brand == 2501 ||
                        $brand == 940 ||
                        $brand == 2353 ||
                        $brand == 2477 ||
                        $brand == 1091 ||
                        $brand == 1095 ||
                        $brand == 2558 ||
                        $brand == 2510 ||
                        $brand == 2480 ||
                        $brand == 2568 ||
                        $brand == 2570 ||
                        $brand == 2565 ||
                        $brand == 2580 ||
                        $brand == 2550 ||
                        $brand == 2589 ||
                        (strtolower($nome_brand) == "ines & marechal") ||
                        (strtolower($nome_brand) == "mr & mrs italy") ||
                        (strtolower($nome_brand) == "pierre louis mascia") ||
                        (strtolower($nome_brand) == "rick owens lilies")) &&

                    ($sku != "151405ABS000006-F0V1Z" &&
                        $sku != "151405ABS000006-F0KUR" &&
                        $sku != "151405ABS000006-F0V20" &&
                        $sku != "151405ABS000006-F0V21" &&
                        $sku != "151405ABS000007-F0V2N" &&
                        $sku != "151405ABS000007-F0KUR" &&
                        $sku != "151405ABS000007-F0V2M" &&
                        $sku != "151405ABS000007-F0L92" &&
                        $sku != "151405ABS000008-F0V1W" &&
                        $sku != "151405ABS000008-F0V1X" &&
                        $sku != "151405ABS000009-F0V8G" &&
                        $sku != "151405ABS000054-F0DVU" &&
                        $sku != "151405ABS000054-F0Z29" &&
                        $sku != "151405ABS000055-F0Y7W" &&
                        $sku != "151405ABS000056-F018C" &&
                        $sku != "151405ABS000056-F018B" &&
                        $sku != "151405ABS000057-F0GN2" &&
                        $sku != "151405ABS000057-F0F89" &&
                        $sku != "151405ABS000057-F0U52" &&
                        $sku != "151405ABS000057-F0W6Q" &&
                        $sku != "151405ABS000057-F0L17" &&
                        $sku != "151405ABS000057-F0V1A" &&
                        $sku != "151405ABS000057-F0A22" &&
                        $sku != "151405ABS000057-F0KUR" &&
                        $sku != "151405ABS000057-F0M8A" &&
                        $sku != "142405ABS000059-F0KUR" &&
                        $sku != "152405ABS000002-F0H42" &&
                        $sku != "152405ABS000003-F0GGC" &&
                        $sku != "152405ABS000004-F022Q" &&
                        $sku != "152405ABS000005-F034D" &&
                        $sku != "152405ABS000005-F034E" &&
                        $sku != "152405ABS000005-F034C" &&
                        $sku != "152405ABS000069-F0H46" &&
                        $sku != "152405ABS000069-F022E" &&
                        $sku != "152405ABS000069-F0KUR" &&
                        $sku != "152405ABS000069-F0NVJ" &&
                        $sku != "152405ABS000069-F016A" &&
                        $sku != "152405ABS000070-F0TMN" &&
                        $sku != "152405ABS000073-F0656" &&
                        $sku != "152405ABS000073-F065B" &&
                        $sku != "152405ABS000073-F0654" &&
                        $sku != "152405ABS000075-F0KUR" &&
                        $sku != "152405ABS000075-F065H" &&
                        $sku != "152405ABS000075-F065K" &&
                        $sku != "152405ABS000075-F065J" &&
                        $sku != "152405ABS000076-F0656" &&
                        $sku != "152405ABS000076-F0654" &&
                        $sku != "152405ABS000078-F0654" &&
                        $sku != "152405ABS000078-F0657" &&
                        $sku != "152405ABS000078-F0655" &&
                        $sku != "152405ABS000079-F0B1X" &&
                        $sku != "152405ABS000080-F065H" &&
                        $sku != "152405ABS000081-F066R" &&
                        $sku != "152405ABS000082-F0676" &&
                        $sku != "152405FBS000013-F0W4Q" &&
                        $sku != "152405FBS000030-F0R2A"
                    )

                ) {

                    $productConfigurable->setVisibility(4);

                } else {
                    $productConfigurable->setVisibility(1);
                }



                $productConfigurable->setUrlKey($url_key);

                //inserimento immagini
                /*for ($k = 0; $k < count($immagini); $k++) {

                    $posizione = strrpos($immagini_nome[$k], ".");
                    $estensione = substr($immagini_nome[$k], $posizione + 1, strlen($immagini_nome[$k]) - ($posizione + 1));

                    $nome_immagine2 = $immagini_nome[$k];
                    $prova = copy($immagini[$k], "../../var/images/" . $nome_immagine2);

                    $file_new = substr($immagini_nome[$k], 0, $posizione);

                    $posizione = strrpos($file_new, "-");
                    $numero_img = substr($file_new, strlen($file_new) - 1, 1);

                    if ($numero_img == "3") {
                        $productConfigurable->addImageToMediaGallery("../../var/images/" . $nome_immagine2, array('image', 'small_image', 'thumbnail'), false, false);

                    } else if ($numero_img == "1") {


                    } else if ($numero_img == "2") {


                    } else {
                        $productConfigurable->addImageToMediaGallery("../../var/images/" . $nome_immagine2, array(""), false, false);

                    }

                }*/

                $productConfigurable->setData('ca_name', $nome);
                $productConfigurable->setData('ca_brand', $brand);
                $productConfigurable->setData('ca_anno', $anno);
                $productConfigurable->setData('ca_stagione', $stagione);
                $productConfigurable->setData('ca_colore', $colore);
                $productConfigurable->setData('ca_filtraggio_colore', $filtraggioColore);

                $queryCarryOver = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku . "' and id_brand='" . $brand . "'";
                $carryOver = $readConnection->fetchOne($queryCarryOver);
                if ($carryOver == null) {
                    $productConfigurable->setData('ca_carryover', 2503);
                } else {
                    $productConfigurable->setData('ca_carryover', 2502);
                }
                $productConfigurable->setData('ca_codice_colore_fornitore', $codice_colore);
                $productConfigurable->setData('ca_codice_produttore', $codice_produttore);
                $productConfigurable->setData('ca_000001', $idSuperColore);
                $productConfigurable->setData('ca_000002', $stringaMotivo);
                $productConfigurable->setData('ca_000003', $stringaSupercomposizione);
                $productConfigurable->setData('ca_000004', $made_in);
                $productConfigurable->setData('ca_000005', $composizione);
                $productConfigurable->setData('ca_000006', $stringaTipborsadonna);
                $productConfigurable->setData('ca_000007', $stringaTipborsauomo);
                $productConfigurable->setData('ca_000008', $dimensioni_borsa_lunghezza);
                $productConfigurable->setData('ca_000009', $dimensioni_borsa_altezza);
                $productConfigurable->setData('ca_000010', $dimensioni_borsa_profondita);
                $productConfigurable->setData('ca_000011', $dimensioni_borsa_altezza_manico);
                $productConfigurable->setData('ca_000012', $dimensioni_borsa_lunghezza_tracolla);
                $productConfigurable->setData('ca_000013', $stringaTipaccessoridonna);
                $productConfigurable->setData('ca_000014', $stringaTipaccessoriuomo);
                $productConfigurable->setData('ca_000015', $cintura_lunghezza);
                $productConfigurable->setData('ca_000016', $cintura_altezza);
                $productConfigurable->setData('ca_000017', $dimensioni_accessorio_lunghezza);
                $productConfigurable->setData('ca_000018', $dimensioni_accessorio_altezza);
                $productConfigurable->setData('ca_000019', $dimensioni_accessorio_profondita);
                $productConfigurable->setData('ca_000020', $dimensioni_calzatura_altezza_tacco);
                $productConfigurable->setData('ca_000021', $dimensioni_calzatura_altezza_plateau);
                $productConfigurable->setData('ca_000022', $dimensioni_calzatura_lunghezza_soletta);
                $productConfigurable->setData('ca_000023', $stringaTipcalzdonna);
                $productConfigurable->setData('ca_000024', $stringaTipcalzuomo);
                $productConfigurable->setData('ca_000025', $stringaTipotaccodonna);
                $productConfigurable->setData('ca_000026', $stringaTiposuola);
                $productConfigurable->setData('ca_000027', $stringaTipopuntadonna);
                $productConfigurable->setData('ca_000028', $stringaTipopuntauomo);
                $productConfigurable->setData('ca_000029', $stringaVestibilitaabiti);
                $productConfigurable->setData('ca_000030', $stringaVestibilitatopwear);
                $productConfigurable->setData('ca_000031', $stringaVestibilitagonne);
                $productConfigurable->setData('ca_000032', $stringaVestibilitapantaloni);
                $productConfigurable->setData('ca_000033', $stringaVestcamicieuomo);
                $productConfigurable->setData('ca_000034', $stringaVestcamiciedonna);
                $productConfigurable->setData('ca_000035', $stringaVestibilitagiacche);
                $productConfigurable->setData('ca_000036', $stringaVestibilitacapispalla);


                $productConfigurable->save();





                    $k=0;

                    // funzione per controllare quali sku sono già presenti per il prodotto configurabile
                    $ids = $productConfigurable->getTypeInstance()->getUsedProductIds();
                    foreach ( $ids as $id ) {

                        $productSimple = Mage::getModel('catalog/product')->load($id);
                        $nome_taglia = $productSimple->getAttributeText('ca_misura');


                        $sku_semplice = $sku . "-" . strtolower($nome_taglia);
                        $nome_semplice = ucfirst(strtolower($lastCategortyDesc . " " . $nome_brand . " " . $nome_taglia));
                        $url_key_semplice = $lastCategortyDesc . "-" . $nome_brand . "-" . $sku_semplice;





                        $productSimple->setName($nome_semplice);
                        $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setPrice($prezzo);
                        $productSimple->setUrlKey($url_key_semplice);
                        $productSimple->setData('ca_name', $nome);
                        $productSimple->setData('ca_brand', $brand);
                        $productSimple->setData('ca_anno', $anno);
                        $productSimple->setData('ca_stagione', $stagione);
                        $productSimple->setData('ca_colore', $colore);
                        $productSimple->setData('ca_filtraggio_colore', $filtraggioColore);


                        if ($carryOver == null) {
                            $productSimple->setData('ca_carryover', 2503);
                        } else {
                            $productSimple->setData('ca_carryover', 2502);
                        }
                        $productSimple->setData('ca_codice_colore_fornitore', $codice_colore);
                        $productSimple->setData('ca_codice_produttore', $codice_produttore);
                        $productSimple->setData('ca_000001', $idSuperColore);
                        $productSimple->setData('ca_000002', $stringaMotivo);
                        $productSimple->setData('ca_000003', $stringaSupercomposizione);
                        $productSimple->setData('ca_000004', $made_in);
                        $productSimple->setData('ca_000005', $composizione);
                        $productSimple->setData('ca_000006', $stringaTipborsadonna);
                        $productSimple->setData('ca_000007', $stringaTipborsauomo);
                        $productSimple->setData('ca_000008', $dimensioni_borsa_lunghezza);
                        $productSimple->setData('ca_000009', $dimensioni_borsa_altezza);
                        $productSimple->setData('ca_000010', $dimensioni_borsa_profondita);
                        $productSimple->setData('ca_000011', $dimensioni_borsa_altezza_manico);
                        $productSimple->setData('ca_000012', $dimensioni_borsa_lunghezza_tracolla);
                        $productSimple->setData('ca_000013', $stringaTipaccessoridonna);
                        $productSimple->setData('ca_000014', $stringaTipaccessoriuomo);
                        $productSimple->setData('ca_000015', $cintura_lunghezza);
                        $productSimple->setData('ca_000016', $cintura_altezza);
                        $productSimple->setData('ca_000017', $dimensioni_accessorio_lunghezza);
                        $productSimple->setData('ca_000018', $dimensioni_accessorio_altezza);
                        $productSimple->setData('ca_000019', $dimensioni_accessorio_profondita);
                        $productSimple->setData('ca_000020', $dimensioni_calzatura_altezza_tacco);
                        $productSimple->setData('ca_000021', $dimensioni_calzatura_altezza_plateau);
                        $productSimple->setData('ca_000022', $dimensioni_calzatura_lunghezza_soletta);
                        $productSimple->setData('ca_000023', $stringaTipcalzdonna);
                        $productSimple->setData('ca_000024', $stringaTipcalzuomo);
                        $productSimple->setData('ca_000025', $stringaTipotaccodonna);
                        $productSimple->setData('ca_000026', $stringaTiposuola);
                        $productSimple->setData('ca_000027', $stringaTipopuntadonna);
                        $productSimple->setData('ca_000028', $stringaTipopuntauomo);
                        $productSimple->setData('ca_000029', $stringaVestibilitaabiti);
                        $productSimple->setData('ca_000030', $stringaVestibilitatopwear);
                        $productSimple->setData('ca_000031', $stringaVestibilitagonne);
                        $productSimple->setData('ca_000032', $stringaVestibilitapantaloni);
                        $productSimple->setData('ca_000033', $stringaVestcamicieuomo);
                        $productSimple->setData('ca_000034', $stringaVestcamiciedonna);
                        $productSimple->setData('ca_000035', $stringaVestibilitagiacche);
                        $productSimple->setData('ca_000036', $stringaVestibilitacapispalla);
                        $productSimple->save();


                    }

                $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                foreach ($pCollection as $process) {
                    $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                }

                }
                else {
                    echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
                }



            echo "<script>alert('Prodotto salvato con successo!');location.replace('../index.php');</script>";


        }
        else {
            echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
        }

    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}

?>