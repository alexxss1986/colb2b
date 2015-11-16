<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Visualizza Prodotto | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">
    <link href="assets/plugins/chosen/chosen.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){

    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['check']) && isset($_REQUEST['sku'])) {

            include("config/percorsoMage.php");
            require_once $MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            include("config/iva.php");
            require('config/sorter.php');

            // recupero l'id del prodotto selezionato e lo sku inserito precedentemente
            $id_prodotto=$_REQUEST['check'];
            $sku_iniziale=$_REQUEST['sku'];


            // recupero il tipo prodotto associato a quell'id
            $product = Mage::getModel('catalog/product')->load($id_prodotto);
            $type_id=$product->getTypeId();


                // se è configurabile recupero solo i campi del prodotto configurabile
                $descrizione=$product->getDescription();
                $sku=$product->getSku();
                $stagioneDB=$product->getData('ca_stagione');
                $annoDB=$product->getData('ca_anno');
                $brandDB=$product->getData('ca_brand');
                $nome=$product->getData('ca_name');
                $prezzo=$product->getPrice();
                $prezzoIva=number_format($prezzo*1.22,2,",","");
                $codice_colore=$product->getData('ca_codice_colore_fornitore');
                $codice_produttore=$product->getData('ca_codice_produttore');
                $supercoloreDB=$product->getData('ca_000001');
                $motivoDB=$product->getData('ca_000002');
                $supercomposizioneDB=$product->getData('ca_000003');
                $made_in=$product->getData('ca_000004');
                $composizione=$product->getData('ca_000005');
                $tipoBorsaDonnaDB=$product->getData('ca_000006');
                $tipoBorsaUomoDB=$product->getData('ca_000007');
                $dimensioni_borsa_lunghezza=$product->getData('ca_000008');
                $dimensioni_borsa_altezza=$product->getData('ca_000009');
                $dimensioni_borsa_profondita=$product->getData('ca_000010');
                $dimensioni_borsa_altezza_manico=$product->getData('ca_000011');
                $dimensioni_borsa_lunghezza_tracolla=$product->getData('ca_000012');
                $tipoAccessoriDonnaDB=$product->getData('ca_000013');
                $tipoAcccessoriUomoDB=$product->getData('ca_000014');
                $cintura_lunghezza=$product->getData('ca_000015');
                $cintura_altezza=$product->getData('ca_000016');
                $dimensioni_accessorio_lunghezza=$product->getData('ca_000017');
                $dimensioni_accessorio_altezza=$product->getData('ca_000018');
                $dimensioni_accessorio_profondita=$product->getData('ca_000019');
                $dimensioni_calzatura_altezza_tacco=$product->getData('ca_000020');
                $dimensioni_calzatura_altezza_plateau=$product->getData('ca_000021');
                $dimensioni_calzatura_lunghezza_soletta=$product->getData('ca_000022');
                $tipoCalzDonnaDB=$product->getData('ca_000023');
                $tipoCalzUomoDB=$product->getData('ca_000024');
                $tipoTaccoDonnaDB=$product->getData('ca_000025');
                $tipoSuolaDB=$product->getData('ca_000026');
                $tipoPuntaDonnaDB=$product->getData('ca_000027');
                $tipoPuntaUomoDB=$product->getData('ca_000028');
                $vestibilitaAbitiDB=$product->getData('ca_000029');
                $vestibilitaTopwearDB=$product->getData('ca_000030');
                $vestibilitaGonneDB=$product->getData('ca_000031');
                $vestibilitaPantaloniDB=$product->getData('ca_000032');
                $vestibilitaCamicieUomoDB=$product->getData('ca_000033');
                $vestibilitaCamicieDonnaDB=$product->getData('ca_000034');
                $vestibilitaGiaccheDB=$product->getData('ca_000035');
                $vestibilitaCapispallaDB=$product->getData('ca_000036');

                $cats = $product->getCategoryIds();
                for ($i=1; $i<count($cats); $i++){
                    $cat = Mage::getModel('catalog/category')->load($cats[$i]);
                    $k=$cat->getLevel()-1;
                    if ($k==1){
                        $categoriaDB=$cats[$i];
                    }
                    else {
                        $k=$k-1;
                        ${'sottoCategoriaDB'.$k}=$cats[$i];
                    }
                }

                $cat = Mage::getModel('catalog/category')->load(2);
                $subcats = $cat->getChildren();
                $k=0;
                while ($subcats!=""){
                    $i=0;
                    foreach(explode(',',$subcats) as $subCatid)
                    {
                        $parentCategory = Mage::getModel('catalog/category')->load($subCatid);
                        if ($k==0){
                            $categoria[$i][0]=$subCatid;
                            $categoria[$i][1]=$parentCategory->getName();
                            if ($subCatid==$categoriaDB){
                                $sottoCat=$subCatid;
                            }
                            $i=$i+1;
                        }
                        else {
                            ${'sottoCategoria'.$k}[$i][0]=$subCatid;
                            ${'sottoCategoria'.$k}[$i][1]=$parentCategory->getName();
                            if ($subCatid==${'sottoCategoriaDB'.$k}){
                                $sottoCat=$subCatid;
                            }
                            $i=$i+1;
                        }


                    }

                    if ($k>0){
                        $oSorter = new ArraySorter();
                        $oSorter->setArray(${'sottoCategoria'.$k});
                        ${'sottoCategoria'.$k}=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);
                    }

                    $k=$k+1;
                    $cat = Mage::getModel('catalog/category')->load($sottoCat);
                    $subcats = $cat->getChildren();
                }



                $supercoloreDB=explode(",",$supercoloreDB);
                $motivoDB=explode(",",$motivoDB);
                $supercomposizioneDB=explode(",",$supercomposizioneDB);
                $tipoBorsaDonnaDB=explode(",",$tipoBorsaDonnaDB);
                $tipoBorsaUomoDB=explode(",",$tipoBorsaUomoDB);
                $tipoAccessoriDonnaDB=explode(",",$tipoAccessoriDonnaDB);
                $tipoAcccessoriUomoDB=explode(",",$tipoAcccessoriUomoDB);
                $tipoCalzDonnaDB=explode(",",$tipoCalzDonnaDB);
                $tipoCalzUomoDB=explode(",",$tipoCalzUomoDB);
                $tipoTaccoDonnaDB=explode(",",$tipoTaccoDonnaDB);
                $tipoSuolaDB=explode(",",$tipoSuolaDB);
                $tipoPuntaDonnaDB=explode(",",$tipoPuntaDonnaDB);
                $tipoPuntaUomoDB=explode(",",$tipoPuntaUomoDB);
                $vestibilitaAbitiDB=explode(",",$vestibilitaAbitiDB);
                $vestibilitaTopwearDB=explode(",",$vestibilitaTopwearDB);
                $vestibilitaGonneDB=explode(",",$vestibilitaGonneDB);
                $vestibilitaPantaloniDB=explode(",",$vestibilitaPantaloniDB);
                $vestibilitaCamicieUomoDB=explode(",",$vestibilitaCamicieUomoDB);
                $vestibilitaCamicieDonnaDB=explode(",",$vestibilitaCamicieDonnaDB);
                $vestibilitaGiaccheDB=explode(",",$vestibilitaGiaccheDB);
                 $vestibilitaCapispallaDB=explode(",",$vestibilitaCapispallaDB);

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($categoria);
                $categoria=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);




                // recupero le opzioni dell'attributo supercolore
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $supercolore[$i][0]=$option['value'];
                    $supercolore[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($supercolore);
                $supercolore=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo motivo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000002");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $motivo[$i][0]=$option['value'];
                    $motivo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($motivo);
                $motivo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo supercomposizione
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000003");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $supercomposizione[$i][0]=$option['value'];
                    $supercomposizione[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($supercomposizione);
                $supercomposizione=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo borsa donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000006");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoBorsaDonna[$i][0]=$option['value'];
                    $tipoBorsaDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoBorsaDonna);
                $tipoBorsaDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo borsa uomo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000007");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoBorsaUomo[$i][0]=$option['value'];
                    $tipoBorsaUomo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoBorsaUomo);
                $tipoBorsaUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo accessori donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000013");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoAccessoriDonna[$i][0]=$option['value'];
                    $tipoAccessoriDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoAccessoriDonna);
                $tipoAccessoriDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo accessori uomo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000014");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoAccessoriUomo[$i][0]=$option['value'];
                    $tipoAccessoriUomo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoAccessoriUomo);
                $tipoAccessoriUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo calzature donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000023");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoCalzDonna[$i][0]=$option['value'];
                    $tipoCalzDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoCalzDonna);
                $tipoCalzDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo calzature uomo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000024");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoCalzUomo[$i][0]=$option['value'];
                    $tipoCalzUomo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoCalzUomo);
                $tipoCalzUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);

                // recupero le opzioni dell'attributo tipo tacco donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000025");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoTaccoDonna[$i][0]=$option['value'];
                    $tipoTaccoDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoTaccoDonna);
                $tipoTaccoDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo suola
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000026");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoSuola[$i][0]=$option['value'];
                    $tipoSuola[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoSuola);
                $tipoSuola=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo punta donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000027");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoPuntaDonna[$i][0]=$option['value'];
                    $tipoPuntaDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoPuntaDonna);
                $tipoPuntaDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo tipo punta uomo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000028");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $tipoPuntaUomo[$i][0]=$option['value'];
                    $tipoPuntaUomo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($tipoPuntaUomo);
                $tipoPuntaUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità abiti
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000029");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestibilitaAbiti[$i][0]=$option['value'];
                    $vestibilitaAbiti[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestibilitaAbiti);
                $vestibilitaAbiti=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità topwear
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000030");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestibilitaTopwear[$i][0]=$option['value'];
                    $vestibilitaTopwear[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestibilitaTopwear);
                $vestibilitaTopwear=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità gonne
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000031");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestibilitaGonne[$i][0]=$option['value'];
                    $vestibilitaGonne[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestibilitaGonne);
                $vestibilitaGonne=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità pantaloni e jeans
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000032");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestibilitaJeans[$i][0]=$option['value'];
                    $vestibilitaJeans[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestibilitaJeans);
                $vestibilitaJeans=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità camicie uomo
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000033");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestCamicieUomo[$i][0]=$option['value'];
                    $vestCamicieUomo[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestCamicieUomo);
                $vestCamicieUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità camicie donna
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000034");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestCamicieDonna[$i][0]=$option['value'];
                    $vestCamicieDonna[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestCamicieDonna);
                $vestCamicieDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità giacche
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000035");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestGiacche[$i][0]=$option['value'];
                    $vestGiacche[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestGiacche);
                $vestGiacche=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo vestibilità capispalla
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000036");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    $vestCapispalla[$i][0]=$option['value'];
                    $vestCapispalla[$i][1]=$option['label'];
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($vestCapispalla);
                $vestCapispalla=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);




                // recupero tutte le categorie "figlie" della default category
                $i=0;
                $cat = Mage::getModel('catalog/category')->load(2);
                $subcats = $cat->getChildren();
                foreach(explode(',',$subcats) as $subCatid)
                {
                    $parentCategory = Mage::getModel('catalog/category')->load($subCatid);
                    $categoria[$i][0]=$subCatid;
                    $categoria[$i][1]=$parentCategory->getName();
                    $i=$i+1;
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($categoria);
                $categoria=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo brand
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                        $brand[$i][0]=$option['value'];
                        $brand[$i][1]=$option['label'];
                        $i=$i+1;

                }


                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($brand);
                $brand=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


                // recupero le opzioni dell'attributo colore
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    // i colori misti non vengono recuperati
                    if (strpos($option['label'],"/")==false){
                        $colore[$i][0]=$option['value'];
                        $colore[$i][1]=$option['label'];
                        $i=$i+1;
                    }
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($colore);
                $colore=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);



                // recupero le opzioni dell'attributo stagione
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_stagione");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    // i colori misti non vengono recuperati
                    if (strpos($option['label'],"/")==false){
                        $stagione[$i][0]=$option['value'];
                        $stagione[$i][1]=$option['label'];
                        $i=$i+1;
                    }
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($stagione);
                $stagione=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);



                // recupero le opzioni dell'attributo stagione
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_anno");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    // i colori misti non vengono recuperati
                    if (strpos($option['label'],"/")==false){
                        $anno[$i][0]=$option['value'];
                        $anno[$i][1]=$option['label'];
                        $i=$i+1;
                    }
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($anno);
                $anno=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);





            ?>
            <!--
                ===========================================================
                BEGIN PAGE
                ===========================================================
                -->
            <div class="wrapper">
            <!-- BEGIN TOP NAV -->
            <?php
            include("config/top.php");
            ?>
            <!-- END TOP NAV -->

            <!-- BEGIN SIDEBAR LEFT -->
            <?php

            include("config/left.php");
            ?>

            <!-- END SIDEBAR LEFT -->






            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content">
            <div class="container-fluid">

            <!-- Begin page heading -->
            <h1 class="page-heading">VISUALIZZA PRODOTTO<!--<small>Sub heading here</small>--></h1>
            <!-- Form inserimento prodotto -->
            <div class="the-box" style="float:left;width:100%">
                <form name="form_visualizza_prodotto" id="visualizza_prodotto_conf" method="post" action="config/modifica_prodotto.php">
                <input type="hidden" name="id_prodotto" value="<?php  echo $id_prodotto ?>" />
                <input type="hidden" name="type_id" value="<?php  echo $type_id ?>" />
                <input type="hidden" name="sku" value="<?php  echo $sku ?>" />
                <div id="form" style="width:100%">
                <div class="form-group">
                    <label class="control-label">Nome *</label>
                    <input type="text" value="<?php  echo $nome ?>" name="nome" placeholder="Nome" class="form-control" id="nome"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Descrizione *</label>
                    <textarea name="descrizione" id="descrizione" class="form-control" style="height:100px" placeholder="Descrizione"><?php  echo $descrizione ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Id</label>
                    <input type="text" value="<?php  echo $sku ?>" name="sku" placeholder="Id prodotto" disabled class="form-control" id="sku"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Codice colore fornitore</label>
                    <input type="text" name="codice_colore" value="<?php  echo $codice_colore ?>" id="codice_colore" class="form-control" placeholder="Codice colore fornitore"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Codice produttore</label>
                    <input type="text" name="codice_produttore" value="<?php  echo $codice_produttore ?>" id="codice_produttore" class="form-control" placeholder="Codice produttore"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Categoria *</label>
                    <select name="categoria" class="form-control select_p" id="categoria" onchange="attivaSottoCategoria(this.value,1,'')" >
                        <option value="">-- Seleziona la categoria --</option>
                        <?php
                        for ($i=0; $i<count($categoria); $i++){
                            echo "<option value=\"".$categoria[$i][0]."\"";
                            if ($categoria[$i][0]==$categoriaDB){
                                echo " selected";
                            }
                            echo ">".$categoria[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" id="rigaSC2">
                    <label class="control-label">Sotto categoria 1 *</label>
                    <select name="sottocategoria1" class="form-control select_p" id="sottocategoria1" onchange="attivaSottoCategoria(this.value,2,'')" >
                        <option value="">-- Seleziona la sottocategoria --</option>
                        <?php
                        for ($i=0; $i<count($sottoCategoria1); $i++){
                            echo "<option value=\"".$sottoCategoria1[$i][0]."\"";
                            if ($sottoCategoria1[$i][0]==$sottoCategoriaDB1){
                                echo " selected";
                            }
                            echo ">".$sottoCategoria1[$i][1]."</option>";
                        }
                        ?>

                    </select>
                </div>


                <?php
                $m=2;
                $parent="";
                while (isset(${'sottoCategoria'.$m})){
                    $parent.=${'sottoCategoriaDB'.($m-1)}."/";
                    echo "<div class=\"form-group\" id=\"rigaSC".($m+1)."\" style=\"margin-top:8px\">
						<label class=\"control-label\">Sotto categoria ".$m." *</label>
						<select name=\"sottocategoria".$m."\" class=\"form-control select_p\" id=\"sottocategoria".$m."\" onchange=\"attivaSottoCategoria(this.value,".($m+1).",'".$parent."')\">
						<option value=\"\">...</option>";

                    for ($i=0; $i<count(${'sottoCategoria'.$m}); $i++){
                        echo "<option value=\"".${'sottoCategoria'.$m}[$i][0]."\"";
                        if (${'sottoCategoria'.$m}[$i][0]==${'sottoCategoriaDB'.$m}){
                            echo " selected";
                            if (${'sottoCategoria'.$m}[$i][1]=="Abiti"){
                                $mode="Abiti";
                            }
                            else if (${'sottoCategoria'.$m}[$i][1]=="Camicie"){
                                $mode="Camicie";
                            }
                            else if (${'sottoCategoria'.$m}[$i][1]=="Pantaloni"){
                                $mode="Pantaloni";
                            }
                            else {
                                $mode="";
                            }
                        }
                        echo ">".${'sottoCategoria'.$m}[$i][1]."</option>";
                    }


                    echo "</select>
					</div>";
                    $m=$m+1;
                }
                ?>
                </div>

                <div class="form-group">
                    <label class="control-label">Prezzo *</label>
                    <input type="text" value="<?php echo $prezzoIva ?>" name="prezzo" id="prezzo" class="form-control" placeholder="Prezzo"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Brand</label>
                    <select name="brand" class="form-control select_p" id="brand" >
                        <option value="">-- Seleziona il brand --</option>
                        <?php
                        for ($i=0; $i<count($brand); $i++){
                            echo "<option value=\"".$brand[$i][0]."\"";
                            if ($brand[$i][0]==$brandDB){
                                echo " selected";
                            }
                            echo ">".$brand[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Stagione *</label>
                    <select name="stagione" class="form-control select_p" id="stagione" >
                        <option value="">-- Seleziona la stagione --</option>
                        <?php
                        for ($i=0; $i<count($stagione); $i++){
                            echo "<option value=\"".$stagione[$i][0]."\"";
                            if ($stagione[$i][0]==$stagioneDB){
                                echo " selected";
                            }
                            echo ">".$stagione[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Anno *</label>
                    <select name="anno" class="form-control select_p" id="anno">
                        <option value="">-- Seleziona l'anno --</option>
                        <?php
                        for ($i=0; $i<count($anno); $i++){
                            echo "<option value=\"".$anno[$i][0]."\"";
                            if ($anno[$i][0]==$annoDB){
                                echo " selected";
                            }
                            echo ">".$anno[$i][1]."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Super colore</label>
                    <select data-placeholder="-- Seleziona il super colore --" name="supercolore[]" class="form-control chosen-select" multiple id="supercolore" >
                        <?php
                        for ($i=0; $i<count($supercolore); $i++){
                            echo "<option value=\"".$supercolore[$i][0]."\"";
                            for ($o=0; $o<count($supercoloreDB); $o++){
                                if ($supercoloreDB[$o]==$supercolore[$i][0]){
                                    echo " selected ";
                                    break;
                                }
                            }
                            echo ">".$supercolore[$i][1]."</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="form-group">
                    <label class="control-label">Motivo</label>
                    <select data-placeholder="-- Seleziona il motivo --" name="motivo[]" class="form-control chosen-select" multiple id="motivo" >
                        <?php
                        for ($i=0; $i<count($motivo); $i++){
                            echo "<option value=\"".$motivo[$i][0]."\"";
                            for ($o=0; $o<count($motivoDB); $o++){
                                if ($motivoDB[$o]==$motivo[$i][0]){
                                    echo " selected ";
                                    break;
                                }
                            }
                            echo ">".$motivo[$i][1]."</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="form-group">
                    <label class="control-label">Supercomposizione</label>
                    <select data-placeholder="-- Seleziona la supercomposizione --" name="supercomposizione[]" class="form-control chosen-select" multiple id="supercomposizione" >
                        <?php
                        for ($i=0; $i<count($supercomposizione); $i++){
                            echo "<option value=\"".$supercomposizione[$i][0]."\"";
                            for ($o=0; $o<count($supercomposizioneDB); $o++){
                                if ($supercomposizioneDB[$o]==$supercomposizione[$i][0]){
                                    echo " selected ";
                                    break;
                                }
                            }
                            echo ">".$supercomposizione[$i][1]."</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="form-group">
                    <label class="control-label">Made In</label>
                    <input type="text" name="made_in" value="<?php echo $made_in ?>" id="made_in" class="form-control" placeholder="Made In"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Composizione</label>
                    <input type="text" name="composizione" value="<?php echo $composizione ?>" id="composizione" class="form-control" placeholder="Composizione"/>
                </div>


                <?php
                $cats = $product->getCategoryIds();
                for ($r=1; $r<count($cats); $r++) {
                    $cat = Mage::getModel('catalog/category')->load($cats[$r]);
                    $nomeCat=$cat->getName();
                    if ($cat->getName()=="Borse donna"){
                        ?>
                        <div class="form-group" id="tipologia_borsa_donna" >
                            <label class="control-label">Tipologia borsa donna</label>
                            <select data-placeholder="-- Seleziona la tipologia borsa donna --" name="tipborsadonna[]" class="form-control chosen-select" multiple id="tipborsadonna" >
                                <?php
                                for ($i=0; $i<count($tipoBorsaDonna); $i++){
                                    echo "<option value=\"".$tipoBorsaDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoBorsaDonnaDB); $o++){
                                        if ($tipoBorsaDonnaDB[$o]==$tipoBorsaDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoBorsaDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dimensioni_borsa_lungh" >
                            <label class="control-label">Dimensioni borsa lunghezza</label>
                            <input type="text" name="dimensioni_borsa_lunghezza" value="<?php echo $dimensioni_borsa_lunghezza ?>" id="dimensioni_borsa_lunghezza" class="form-control" placeholder="Dimensioni borsa lunghezza"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_alt" >
                            <label class="control-label">Dimensioni borsa altezza</label>
                            <input type="text" name="dimensioni_borsa_altezza" value="<?php echo $dimensioni_borsa_altezza ?>" id="dimensioni_borsa_altezza" class="form-control" placeholder="Dimensioni borsa altezza"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_prof" >
                            <label class="control-label">Dimensioni borsa profondità</label>
                            <input type="text" name="dimensioni_borsa_profondita" value="<?php echo $dimensioni_borsa_profondita ?>" id="dimensioni_borsa_profondita" class="form-control" placeholder="Dimensioni borsa profondità"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_alt_manico" >
                            <label class="control-label">Dimensioni borsa altezza manico</label>
                            <input type="text" name="dimensioni_borsa_altezza_manico" value="<?php echo $dimensioni_borsa_altezza_manico ?>" id="dimensioni_borsa_altezza_manico" class="form-control" placeholder="Dimensioni borsa altezza manico"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_lungh_trac" >
                            <label class="control-label">Dimensioni borsa lunghezza tracolla</label>
                            <input type="text" name="dimensioni_borsa_lunghezza_tracolla" value="<?php echo $dimensioni_borsa_lunghezza_tracolla ?>" id="dimensioni_borsa_lunghezza_tracolla" class="form-control" placeholder="Dimensioni borsa lunghezza tracolla"/>
                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Borse uomo"){
                        ?>
                        <div class="form-group" id="tipologia_borsa_donna" >
                            <label class="control-label">Tipologia borsa donna</label>
                            <select data-placeholder="-- Seleziona la tipologia borsa donna --" name="tipborsadonna[]" class="form-control chosen-select" multiple id="tipborsadonna" >
                                <?php
                                for ($i=0; $i<count($tipoBorsaDonna); $i++){
                                    echo "<option value=\"".$tipoBorsaDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoBorsaDonnaDB); $o++){
                                        if ($tipoBorsaDonnaDB[$o]==$tipoBorsaDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoBorsaDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="tipologia_borsa_uomo" >
                            <label class="control-label">Tipologia borsa uomo</label>
                            <select data-placeholder="-- Seleziona la tipologia borsa uomo --" name="tipborsauomo[]" class="form-control chosen-select" multiple id="tipborsauomo" >
                                <?php
                                for ($i=0; $i<count($tipoBorsaUomo); $i++){
                                    echo "<option value=\"".$tipoBorsaUomo[$i][0]."\"";
                                    for ($o=0; $o<count($tipoBorsaUomoDB); $o++){
                                        if ($tipoBorsaUomoDB[$o]==$tipoBorsaUomo[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoBorsaUomo[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dimensioni_borsa_lungh" >
                            <label class="control-label">Dimensioni borsa lunghezza</label>
                            <input type="text" name="dimensioni_borsa_lunghezza" value="<?php echo $dimensioni_borsa_lunghezza ?>" id="dimensioni_borsa_lunghezza" class="form-control" placeholder="Dimensioni borsa lunghezza"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_alt" >
                            <label class="control-label">Dimensioni borsa altezza</label>
                            <input type="text" name="dimensioni_borsa_altezza" value="<?php echo $dimensioni_borsa_altezza ?>" id="dimensioni_borsa_altezza" class="form-control" placeholder="Dimensioni borsa altezza"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_prof" >
                            <label class="control-label">Dimensioni borsa profondità</label>
                            <input type="text" name="dimensioni_borsa_profondita" value="<?php echo $dimensioni_borsa_profondita ?>" id="dimensioni_borsa_profondita" class="form-control" placeholder="Dimensioni borsa profondità"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_alt_manico" >
                            <label class="control-label">Dimensioni borsa altezza manico</label>
                            <input type="text" name="dimensioni_borsa_altezza_manico" value="<?php echo $dimensioni_borsa_altezza_manico ?>" id="dimensioni_borsa_altezza_manico" class="form-control" placeholder="Dimensioni borsa altezza manico"/>
                        </div>
                        <div class="form-group" id="dimensioni_borsa_lungh_trac" >
                            <label class="control-label">Dimensioni borsa lunghezza tracolla</label>
                            <input type="text" name="dimensioni_borsa_lunghezza_tracolla" value="<?php echo $dimensioni_borsa_lunghezza_tracolla ?>" id="dimensioni_borsa_lunghezza_tracolla" class="form-control" placeholder="Dimensioni borsa lunghezza tracolla"/>
                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Accessori donna"){
                        ?>
                        <div class="form-group" id="tip_accessori_donna" >
                            <label class="control-label">Tipologia accessori donna</label>
                            <select data-placeholder="-- Seleziona la tipologia accessori donna --" name="tipaccessoridonna[]" class="form-control chosen-select" multiple id="tipaccessoridonna" >
                                <?php
                                for ($i=0; $i<count($tipoAccessoriDonna); $i++){
                                    echo "<option value=\"".$tipoAccessoriDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoAccessoriDonnaDB); $o++){
                                        if ($tipoAccessoriDonnaDB[$o]==$tipoAccessoriDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoAccessoriDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dim_accessorio_lungh" >
                            <label class="control-label">Dimensioni accessorio lunghezza</label>
                            <input type="text" name="dimensioni_accessorio_lunghezza" value="<?php echo $dimensioni_accessorio_lunghezza ?>" id="dimensioni_accessorio_lunghezza" class="form-control" placeholder="Dimensioni accessorio lunghezza"/>
                        </div>
                        <div class="form-group" id="dim_accessorio_alt" >
                            <label class="control-label">Dimensioni accessorio altezza</label>
                            <input type="text" name="dimensioni_accessorio_altezza" value="<?php echo $dimensioni_accessorio_altezza ?>" id="dimensioni_accessorio_altezza" class="form-control" placeholder="Dimensioni accessorio altezza"/>
                        </div>
                        <div class="form-group" id="dim_accessorio_prof" >
                            <label class="control-label">Dimensioni accessorio profondità</label>
                            <input type="text" name="dimensioni_accessorio_profondita" value="<?php echo $dimensioni_accessorio_profondita ?>" id="dimensioni_accessorio_profondita" class="form-control" placeholder="Dimensioni accessorio profondità"/>
                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Accessori uomo"){
                        ?>
                        <div class="form-group" id="tip_accessori_uomo" >
                            <label class="control-label">Tipologia accessori uomo</label>
                            <select data-placeholder="-- Seleziona la tipologia accessori donna --" name="tipaccessoriuomo[]" class="form-control chosen-select" multiple id="tipaccessoriuomo" >
                                <?php
                                for ($i=0; $i<count($tipoAccessoriUomo); $i++){
                                    echo "<option value=\"".$tipoAccessoriUomo[$i][0]."\"";
                                    for ($o=0; $o<count($tipoAccessoriUomoDB); $o++){
                                        if ($tipoAccessoriUomoDB[$o]==$tipoAccessoriUomo[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoAccessoriUomo[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dim_accessorio_lungh" >
                            <label class="control-label">Dimensioni accessorio lunghezza</label>
                            <input type="text" name="dimensioni_accessorio_lunghezza" value="<?php echo $dimensioni_accessorio_lunghezza ?>" id="dimensioni_accessorio_lunghezza" class="form-control" placeholder="Dimensioni accessorio lunghezza"/>
                        </div>
                        <div class="form-group" id="dim_accessorio_alt" >
                            <label class="control-label">Dimensioni accessorio altezza</label>
                            <input type="text" name="dimensioni_accessorio_altezza" value="<?php echo $dimensioni_accessorio_altezza ?>" id="dimensioni_accessorio_altezza" class="form-control" placeholder="Dimensioni accessorio altezza"/>
                        </div>
                        <div class="form-group" id="dim_accessorio_prof" >
                            <label class="control-label">Dimensioni accessorio profondità</label>
                            <input type="text" name="dimensioni_accessorio_profondita" value="<?php echo $dimensioni_accessorio_profondita ?>" id="dimensioni_accessorio_profondita" class="form-control" placeholder="Dimensioni accessorio profondità"/>
                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Calzature donna"){
                        ?>
                        <div class="form-group" id="tip_calz_donna" >
                            <label class="control-label">Tipologia calzature donna</label>
                            <select data-placeholder="-- Seleziona la tipologia calzature donna --" name="tipcalzdonna[]" class="form-control chosen-select" multiple id="tipcalzdonna" >
                                <?php
                                for ($i=0; $i<count($tipoCalzDonna); $i++){
                                    echo "<option value=\"".$tipoCalzDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoCalzDonnaDB); $o++){
                                        if ($tipoCalzDonnaDB[$o]==$tipoCalzDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoCalzDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dim_calzatura_alt_tacco" >
                            <label class="control-label">Dimensioni calzatura altezza tacco</label>
                            <input type="text" name="dimensioni_calzatura_altezza_tacco" value="<?php echo $dimensioni_calzatura_altezza_tacco ?>" id="dimensioni_calzatura_altezza_tacco" class="form-control" placeholder="Dimensioni calzatura altezza tacco"/>
                        </div>
                        <div class="form-group" id="dim_calzatura_alt_plateau" >
                            <label class="control-label">Dimensioni calzatura altezza plateau</label>
                            <input type="text" name="dimensioni_calzatura_altezza_plateau" value="<?php echo $dimensioni_calzatura_altezza_plateau ?>" id="dimensioni_calzatura_altezza_plateau" class="form-control" placeholder="Dimensioni calzatura altezza plateau"/>
                        </div>
                        <div class="form-group" id="dim_calzatura_lungh_soletta" >
                            <label class="control-label">Dimensioni calzatura lunghezza soletta</label>
                            <input type="text" name="dimensioni_calzatura_lunghezza_soletta" value="<?php echo $dimensioni_calzatura_lunghezza_soletta ?>" id="dimensioni_calzatura_lunghezza_soletta" class="form-control" placeholder="Dimensioni calzatura lunghezza soletta"/>
                        </div>
                        <div class="form-group" id="tipo_tacco_donna" >
                            <label class="control-label">Tipo tacco donna</label>
                            <select data-placeholder="-- Seleziona il tipo tacco donna --" name="tipotaccodonna[]" class="form-control chosen-select" multiple id="tipotaccodonna" >
                                <?php
                                for ($i=0; $i<count($tipoTaccoDonna); $i++){
                                    echo "<option value=\"".$tipoTaccoDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoTaccoDonnaDB); $o++){
                                        if ($tipoTaccoDonnaDB[$o]==$tipoTaccoDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoTaccoDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="tipo_suola" >
                            <label class="control-label">Tipo suola</label>
                            <select data-placeholder="-- Seleziona il tipo suola --" name="tiposuola[]" class="form-control chosen-select" multiple id="tiposuola" >
                                <?php
                                for ($i=0; $i<count($tipoSuola); $i++){
                                    echo "<option value=\"".$tipoSuola[$i][0]."\"";
                                    for ($o=0; $o<count($tipoSuolaDB); $o++){
                                        if ($tipoSuolaDB[$o]==$tipoSuola[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoSuola[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="tipo_punta_donna" >
                            <label class="control-label">Tipo punta donna</label>
                            <select data-placeholder="-- Seleziona il tipo punta donna --" name="tipopuntadonna[]" class="form-control chosen-select" multiple id="tipopuntadonna" >
                                <?php
                                for ($i=0; $i<count($tipoPuntaDonna); $i++){
                                    echo "<option value=\"".$tipoPuntaDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoPuntaDonnaDB); $o++){
                                        if ($tipoPuntaDonnaDB[$o]==$tipoPuntaDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoPuntaDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Calzature uomo"){
                        ?>
                        <div class="form-group" id="tip_calz_uomo" >
                            <label class="control-label">Tipologia calzature uomo</label>
                            <select data-placeholder="-- Seleziona la tipologia calzature uomo --" name="tipcalzuomo[]" class="form-control chosen-select" multiple id="tipcalzuomo" >
                                <?php
                                for ($i=0; $i<count($tipoCalzUomo); $i++){
                                    echo "<option value=\"".$tipoCalzUomo[$i][0]."\"";
                                    for ($o=0; $o<count($tipoCalzUomoDB); $o++){
                                        if ($tipooCalzUomoDB[$o]==$tipoCalzUomo[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoCalzUomo[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="dim_calzatura_alt_tacco" >
                            <label class="control-label">Dimensioni calzatura altezza tacco</label>
                            <input type="text" name="dimensioni_calzatura_altezza_tacco" value="<?php echo $dimensioni_calzatura_altezza_tacco ?>" id="dimensioni_calzatura_altezza_tacco" class="form-control" placeholder="Dimensioni calzatura altezza tacco"/>
                        </div>
                        <div class="form-group" id="dim_calzatura_alt_plateau" >
                            <label class="control-label">Dimensioni calzatura altezza plateau</label>
                            <input type="text" name="dimensioni_calzatura_altezza_plateau" value="<?php echo $dimensioni_calzatura_altezza_plateau ?>" id="dimensioni_calzatura_altezza_plateau" class="form-control" placeholder="Dimensioni calzatura altezza plateau"/>
                        </div>
                        <div class="form-group" id="dim_calzatura_lungh_soletta" >
                            <label class="control-label">Dimensioni calzatura lunghezza soletta</label>
                            <input type="text" name="dimensioni_calzatura_lunghezza_soletta" value="<?php echo $dimensioni_calzatura_lunghezza_soletta ?>" id="dimensioni_calzatura_lunghezza_soletta" class="form-control" placeholder="Dimensioni calzatura lunghezza soletta"/>
                        </div>
                        <div class="form-group" id="tipo_tacco_donna" >
                            <label class="control-label">Tipo tacco donna</label>
                            <select data-placeholder="-- Seleziona il tipo tacco donna --" name="tipotaccodonna[]" class="form-control chosen-select" multiple id="tipotaccodonna" >
                                <?php
                                for ($i=0; $i<count($tipoTaccoDonna); $i++){
                                    echo "<option value=\"".$tipoTaccoDonna[$i][0]."\"";
                                    for ($o=0; $o<count($tipoTaccoDonnaDB); $o++){
                                        if ($tipoTaccoDonnaDB[$o]==$tipoTaccoDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoTaccoDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="tipo_suola" >
                            <label class="control-label">Tipo suola</label>
                            <select data-placeholder="-- Seleziona il tipo suola --" name="tiposuola[]" class="form-control chosen-select" multiple id="tiposuola" >
                                <?php
                                for ($i=0; $i<count($tipoSuola); $i++){
                                    echo "<option value=\"".$tipoSuola[$i][0]."\"";
                                    for ($o=0; $o<count($tipoSuolaDB); $o++){
                                        if ($tipoSuolaDB[$o]==$tipoSuola[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoSuola[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                        <div class="form-group" id="tipo_punta_uomo" >
                            <label class="control-label">Tipo punta uomo</label>
                            <select data-placeholder="-- Seleziona il tipo punta uomo --" name="tipopuntauomo[]" class="form-control chosen-select" multiple id="tipopuntauomo" >
                                <?php
                                for ($i=0; $i<count($tipoPuntaUomo); $i++){
                                    echo "<option value=\"".$tipoPuntaUomo[$i][0]."\"";
                                    for ($o=0; $o<count($tipoPuntaUomoDB); $o++){
                                        if ($tipoPuntaUomoDB[$o]==$tipoPuntaUomo[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$tipoPuntaUomo[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Abiti"){
                        ?>
                        <div class="form-group" id="vestibilita_abiti" >
                            <label class="control-label">Vestibilità abiti</label>
                            <select data-placeholder="-- Seleziona la vestibilità abiti --" name="vestibilitaabiti[]" class="form-control chosen-select" multiple id="vestibilitaabiti" >
                                <?php
                                for ($i=0; $i<count($vestibilitaAbiti); $i++){
                                    echo "<option value=\"".$vestibilitaAbiti[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaAbitiDB); $o++){
                                        if ($vestibilitaAbitiDB[$o]==$vestibilitaAbiti[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestibilitaAbiti[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>

                    <?php
                    }
                    if ($cat->getName()=="Topwear"){
                        ?>
                        <div class="form-group" id="vestibilita_topwear" >
                            <label class="control-label">Vestibilità topwear</label>
                            <select data-placeholder="-- Seleziona la vestibilità topwear --" name="vestibilitatopwear[]" class="form-control chosen-select" multiple id="vestibilitatopwear" >
                                <?php
                                for ($i=0; $i<count($vestibilitaTopwear); $i++){
                                    echo "<option value=\"".$vestibilitaTopwear[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaTopwearDB); $o++){
                                        if ($vestibilitaTopwearDB[$o]==$vestibilitaTopwear[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestibilitaTopwear[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Pantaloni"){
                        ?>

                        <div class="form-group" id="vestibilita_pantaloni" >
                            <label class="control-label">Vestibilità pantaloni e jeans</label>
                            <select data-placeholder="-- Seleziona la vestibilità pantaloni e jeans --" name="vestibilitapantaloni[]" class="form-control chosen-select" multiple id="vestibilitapantaloni" >
                                <?php
                                for ($i=0; $i<count($vestibilitaJeans); $i++){
                                    echo "<option value=\"".$vestibilitaJeans[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaJeansDB); $o++){
                                        if ($vestibilitaJeansDB[$o]==$vestibilitaJeans[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestibilitaJeans[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>


                    <?php
                    }
                    if ($cat->getName()=="Jeans"){
                        ?>

                        <div class="form-group" id="vestibilita_pantaloni" >
                            <label class="control-label">Vestibilità pantaloni e jeans</label>
                            <select data-placeholder="-- Seleziona la vestibilità pantaloni e jeans --" name="vestibilitapantaloni[]" class="form-control chosen-select" multiple id="vestibilitapantaloni" >
                                <?php
                                for ($i=0; $i<count($vestibilitaJeans); $i++){
                                    echo "<option value=\"".$vestibilitaJeans[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaJeansDB); $o++){
                                        if ($vestibilitaJeansDB[$o]==$vestibilitaJeans[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestibilitaJeans[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>


                    <?php
                    }
                    if ($cat->getName()=="Gonne"){
                        ?>

                        <div class="form-group" id="vestibilita_gonne" >
                            <label class="control-label">Vestibilità gonne</label>
                            <select data-placeholder="-- Seleziona la vestibilità gonne --" name="vestibilitagonne[]" class="form-control chosen-select" multiple id="vestibilitagonne" >
                                <?php
                                for ($i=0; $i<count($vestibilitaGonne); $i++){
                                    echo "<option value=\"".$vestibilitaGonne[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaGonneDB); $o++){
                                        if ($vestibilitaGonneDB[$o]==$vestibilitaGonne[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestibilitaGonne[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>


                    <?php
                    }
                    if (($cat->getName()=="Camicie" && $cat->getId()==9)){
                        ?>

                        <div class="form-group" id="vestibilita_camicie_donna" >
                            <label class="control-label">Vestibilità camicie donna</label>
                            <select data-placeholder="-- Seleziona la vestibilità camicie donna --" name="vestcamiciedonna[]" class="form-control chosen-select" multiple id="vestcamiciedonna" >
                                <?php
                                for ($i=0; $i<count($vestCamicieDonna); $i++){
                                    echo "<option value=\"".$vestCamicieDonna[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaCamicieDonnaDB); $o++){
                                        if ($vestibilitaCamicieDonnaDB[$o]==$vestCamicieDonna[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestCamicieDonna[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>

                    <?php
                    }
                    if ($cat->getName()=="Camicie" && $cat->getId()==22){
                        ?>

                        <div class="form-group" id="vestibilita_camicie_uomo" >
                            <label class="control-label">Vestibilità camicie uomo</label>
                            <select data-placeholder="-- Seleziona la vestibilità camicie uomo --" name="vestcamicieuomo[]" class="form-control chosen-select" multiple id="vestcamicieuomo" >
                                <?php
                                for ($i=0; $i<count($vestCamicieUomo); $i++){
                                    echo "<option value=\"".$vestCamicieUomo[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaCamicieUomoDB); $o++){
                                        if ($vestibilitaCamicieUomoDB[$o]==$vestCamicieUomo[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestCamicieUomo[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>


                    <?php
                    }
                    if ($cat->getName()=="Capispalla"){
                        ?>

                        <div class="form-group" id="vestibilita_capispalla" >
                            <label class="control-label">Vestibilità capispalla</label>
                            <select data-placeholder="-- Seleziona la vestibilità capispalla --" name="vestibilitacapispalla[]" class="form-control chosen-select" multiple id="vestibilitacapispalla" >
                                <?php
                                for ($i=0; $i<count($vestCapispalla); $i++){
                                    echo "<option value=\"".$vestCapispalla[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaCapispallaDB); $o++){
                                        if ($vestibilitaCapispallaDB[$o]==$vestCapispalla[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestCapispalla[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Giacche/blazer" || $cat->getName()=="Blazer"){
                        ?>

                        <div class="form-group" id="vestibilita_giacche" >
                            <label class="control-label">Vestibilità giacche</label>
                            <select data-placeholder="-- Seleziona la vestibilità giacche --" name="vestibilitagiacche[]" class="form-control chosen-select" multiple id="vestibilitagiacche" >
                                <?php
                                for ($i=0; $i<count($vestGiacche); $i++){
                                    echo "<option value=\"".$vestGiacche[$i][0]."\"";
                                    for ($o=0; $o<count($vestibilitaGiaccheDB); $o++){
                                        if ($vestibilitaGiaccheDB[$o]==$vestGiacche[$i][0]){
                                            echo " selected ";
                                            break;
                                        }
                                    }
                                    echo ">".$vestGiacche[$i][1]."</option>";
                                }
                                ?>
                            </select>

                        </div>
                    <?php
                    }
                    if ($cat->getName()=="Cinture"){
                        ?>
                        <div class="form-group" id="cint_lunghezza" >
                            <label class="control-label">Cintura lunghezza</label>
                            <input type="text" name="cintura_lunghezza" value="<?php echo $cintura_lunghezza ?>" id="cintura_lunghezza" class="form-control" placeholder="Cintura Lunghezza"/>
                        </div>
                        <div class="form-group" id="cint_altezza" >
                            <label class="control-label">Cintura altezza</label>
                            <input type="text" name="cintura_altezza" value="<?php echo $cintura_altezza ?>" id="cintura_altezza" class="form-control" placeholder="Cintura altezza"/>
                        </div>

                    <?php
                    }
                }
                ?>
                                <p style="margin-top:30px;font-size:10px;float:left;width:100%">* Campi obbligatori</p>
                                <div class="form-group" style="margin-top:30px">

                                    <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='modifica-prodotto.php?sku=$sku_iniziale'"; ?>" />Indietro</button>
                                    <button name="modifica" class="btn btn-success active" style="height: 35px;font-size: 15px;float:right"  type="submit" />Modifica</button>

                                </div>
                                <div class="progress no-rounded progress-striped active" id="loading" style="width:71%;display:none">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                        <span class="sr-only">40% Complete (success)</span>
                                    </div>
                                </div>


                </form>

            </div>
            </div>
            <?php include("config/footer.php") ?>
            </div>
            </div>
        <?php
        }
        else {
            echo "<script>alert('Errore nella visualizzazione della pagina!');window.location='index.php'</script>";
        }
    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');window.location='index.php'</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');window.location='index.php'</script>";
}

?>

<!--
===========================================================
END PAGE
===========================================================
-->

<!--
===========================================================
Placed at the end of the document so the pages load faster
===========================================================
-->
<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/retina/retina.min.js"></script>
<script src="assets/plugins/nicescroll/jquery.nicescroll.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>

<!-- PLUGINS -->
<script src="assets/plugins/validator/bootstrapValidator.js"></script>
<script src="assets/plugins/slider/bootstrap-slider.js"></script>
<script src="assets/plugins/chosen/chosen.jquery.min.js"></script>


<script src="assets/plugins/validator/example.js"></script>


<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>

</body>
</html>