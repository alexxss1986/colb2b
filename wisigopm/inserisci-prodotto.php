<style>
    .chosen-container {
        width:auto !important;
    }
</style>
<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inserisci Prodotto | Wisigo Product Management</title>
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

        include("config/percorsoMage.php");
        require_once $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        require('config/sorter.php');

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
            $tipBorsaDonna[$i][0]=$option['value'];
            $tipBorsaDonna[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipBorsaDonna);
        $tipBorsaDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo tipo borsa uomo
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000007");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $tipBorsaUomo[$i][0]=$option['value'];
            $tipBorsaUomo[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipBorsaUomo);
        $tipBorsaUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo tipo accessori donna
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000013");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $tipAccessoriDonna[$i][0]=$option['value'];
            $tipAccessoriDonna[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipAccessoriDonna);
        $tipAccessoriDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo tipo accessori uomo
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000014");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $tipAccessoriUomo[$i][0]=$option['value'];
            $tipAccessoriUomo[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipAccessoriUomo);
        $tipAccessoriUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo tipo calzature donna
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000023");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $tipCalzDonna[$i][0]=$option['value'];
            $tipCalzDonna[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipCalzDonna);
        $tipCalzDonna=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo tipo calzature uomo
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_000024");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $tipCalzUomo[$i][0]=$option['value'];
            $tipCalzUomo[$i][1]=$option['label'];
            $i=$i+1;
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($tipCalzUomo);
        $tipCalzUomo=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);

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


        // recupero le opzioni dell'attributo misura
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            // i colori misti non vengono recuperati
            if (strpos($option['label'],"/")==false){
                $taglia[$i][0]=$option['value'];
                $taglia[$i][1]=$option['label'];
                $i=$i+1;
            }
        }

        // ordinamento
        $oSorter = new ArraySorter();
        $oSorter->setArray($taglia);
        $taglia=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


        // recupero le opzioni dell'attributo brand
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            // i colori misti non vengono recuperati
            if (strpos($option['label'],"/")==false){
                $brand[$i][0]=$option['value'];
                $brand[$i][1]=$option['label'];
                $i=$i+1;
            }
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



        $stringQuery = "select id,descrizione from " . $resource->getTableName('scalariusa');
        $listaScalari = $readConnection->fetchAll($stringQuery);
        $i=0;
        foreach ($listaScalari as $row) {
            $scalare[$i][0] = $row['id'];
            $scalare[$i][1] = $row['descrizione'];
            $i=$i+1;
        }

        // azzero le variabili di sessione
        unset($_SESSION['taglie_s']);
        unset($_SESSION['scalari_s']);
        unset($_SESSION['taglia']);
        unset($_SESSION['scalare']);
        unset($_SESSION['qta']);
        unset($_SESSION['taglie_scelte']);
        unset($_SESSION['scalari_scelti']);

        // setto tutte le taglie e i colori presenti in magento in due variabili di sessione
        $_SESSION['taglia']=$taglia;
        $_SESSION['scalare']=$scalare;


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
        <h1 class="page-heading">INSERISCI PRODOTTO<!--<small>Sub heading here</small>--></h1>
        <!-- Form inserimento prodotto -->
        <div class="the-box">
        <div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p id="errore"></p>
        </div>
        <div class="alert alert-success fade in alert-dismissable" id="riga_errore" style="display:none">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p id="errore"></p>
        </div>
        <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                <span class="sr-only">40% Complete (success)</span>
            </div>
        </div>
        <form id="inserisci_prodotto"  name="form_prodotto" method="post" action="config/inserisci-prodotto.php" enctype="multipart/form-data" >
        <!--<h4 class="small-title">FORM PER L'INSERIMENTO DI UN NUOVO PRODOTTO</h4>-->
        <div class="form-group">
            <label class="control-label">Nome *</label>
            <input type="text" name="nome" class="form-control" placeholder="Nome" id="nome"/>

        </div>
        <div class="form-group">
            <label class="control-label">Descrizione *</label>
            <textarea name="descrizione" id="descrizione" class="form-control" placeholder="Descrizione" style="height:100px"></textarea>

        </div>
        <div class="form-group">
            <label class="control-label">Id *</label>
            <input type="text" name="id" id="id" class="form-control" placeholder="Id Prodotto"/>
        </div>
        <div class="form-group">
            <label class="control-label">Codice colore fornitore</label>
            <input type="text" name="codice_colore" id="codice_colore" class="form-control" placeholder="Codice colore fornitore"/>
        </div>
        <div class="form-group">
            <label class="control-label">Codice produttore</label>
            <input type="text" name="codice_produttore" id="codice_produttore" class="form-control" placeholder="Codice produttore"/>
        </div>

        <!-- ogni volta che cambia il valore della select viene attivata la funzione per recuperare le sottocategorie !-->
        <div class="form-group">
            <label class="control-label">Categoria *</label>
            <select name="categoria" class="form-control" placeholder="Categoria *" id="categoria" onchange="attivaSottoCategoria(this.value,1,'')">
                <option value="">-- Seleziona la categoria --</option>
                <?php
                for ($i=0; $i<count($categoria); $i++){
                    echo "<option value=\"".$categoria[$i][0]."\">".$categoria[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="rigaSC2" style="display:none">
            <label class="control-label">Sotto categoria 1 *</label>
            <select name="sottocategoria1" class="form-control" id="sottocategoria1">
                <option value="">-- Seleziona la sottocategoria --</option>

            </select>
        </div>
        <div class="form-group" id="rigaSC3" style="display:none">
            <label class="control-label">Sotto categoria 2 *</label>
            <select name="sottocategoria2" class="form-control" id="sottocategoria2">
                <option value="">-- Seleziona la sottocategoria --</option>

            </select>
        </div>
        <div class="form-group" id="rigaSC4" style="display:none">
            <label class="control-label">Sotto categoria 3</label>
            <select name="sottocategoria3" class="form-control" id="sottocategoria3">
                <option value="">-- Seleziona la sottocategoria --</option>

            </select>
        </div>
        <div id="rigaSottoCat">
        </div>
        <div class="form-group">
            <label class="control-label">Prezzo *</label>
            <input type="text" name="prezzo" id="prezzo" class="form-control" placeholder="Prezzo"/>
        </div>
        <div class="form-group">
            <label class="control-label">Brand *</label>
            <select name="brand" class="form-control" id="brand" >
                <option value="">-- Seleziona il brand --</option>
                <?php
                for ($i=0; $i<count($brand); $i++){
                    echo "<option value=\"".$brand[$i][0]."\">".$brand[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label class="control-label">Stagione *</label>
            <select name="stagione" class="form-control" id="stagione">
                <option value="">-- Seleziona la stagione --</option>
                <?php
                for ($i=0; $i<count($stagione); $i++){
                    echo "<option value=\"".$stagione[$i][0]."\">".$stagione[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label class="control-label">Anno *</label>
            <select name="anno" class="form-control" id="anno">
                <option value="">-- Seleziona l'anno --</option>
                <?php
                for ($i=0; $i<count($anno); $i++){
                    echo "<option value=\"".$anno[$i][0]."\">".$anno[$i][1]."</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Immagini *</label>
            <input type="file" name="immagini[]" id="immagini" multiple class="multi form-control"/>
        </div>
        <div class="form-group">
            <label class="control-label">Super colore</label>
            <select data-placeholder="-- Seleziona il super colore --" name="supercolore[]" class="form-control chosen-select" multiple id="supercolore" >
                <?php
                for ($i=0; $i<count($supercolore); $i++){
                    echo "<option value=\"".$supercolore[$i][0]."\">".$supercolore[$i][1]."</option>";
                }
                ?>
            </select>

        </div>

        <div class="form-group">
            <label class="control-label">Motivo</label>
            <select data-placeholder="-- Seleziona il motivo --" name="motivo[]" class="form-control chosen-select" multiple id="motivo" >
                <?php
                for ($i=0; $i<count($motivo); $i++){
                    echo "<option value=\"".$motivo[$i][0]."\">".$motivo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label class="control-label">Supercomposizione</label>
            <select data-placeholder="-- Seleziona la supercomposizione --" name="supercomposizione[]" class="form-control chosen-select" multiple id="supercomposizione" >
                <?php
                for ($i=0; $i<count($supercomposizione); $i++){
                    echo "<option value=\"".$supercomposizione[$i][0]."\">".$supercomposizione[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group">
            <label class="control-label">Made In</label>
            <input type="text" name="made_in" id="made_in" class="form-control" placeholder="Made In"/>
        </div>
        <div class="form-group">
            <label class="control-label">Composizione</label>
            <input type="text" name="composizione" id="composizione" class="form-control" placeholder="Composizione"/>
        </div>
            <div class="form-group" id="tipologia_borsa_donna" style="display:none">
                <label class="control-label">Tipologia borsa donna</label>
                <select data-placeholder="-- Seleziona la tipologia borsa donna --" name="tipborsadonna[]" class="form-control chosen-select" multiple id="tipborsadonna" >
                    <?php
                    for ($i=0; $i<count($tipBorsaDonna); $i++){
                        echo "<option value=\"".$tipBorsaDonna[$i][0]."\">".$tipBorsaDonna[$i][1]."</option>";
                    }
                    ?>
                </select>

            </div>
        <div class="form-group" id="tipologia_borsa_uomo" style="display:none">
            <label class="control-label">Tipologia borsa uomo</label>
            <select data-placeholder="-- Seleziona la tipologia borsa uomo --" name="tipborsauomo[]" class="form-control chosen-select" multiple id="tipborsauomo" >
                <?php
                for ($i=0; $i<count($tipBorsaUomo); $i++){
                    echo "<option value=\"".$tipBorsaUomo[$i][0]."\">".$tipBorsaUomo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="dimensioni_borsa_lungh" style="display:none">
            <label class="control-label">Dimensioni borsa lunghezza</label>
            <input type="text" name="dimensioni_borsa_lunghezza" id="dimensioni_borsa_lunghezza" class="form-control" placeholder="Dimensioni borsa lunghezza"/>
        </div>
        <div class="form-group" id="dimensioni_borsa_alt" style="display:none">
            <label class="control-label">Dimensioni borsa altezza</label>
            <input type="text" name="dimensioni_borsa_altezza" id="dimensioni_borsa_altezza" class="form-control" placeholder="Dimensioni borsa altezza"/>
        </div>
        <div class="form-group" id="dimensioni_borsa_prof" style="display:none">
            <label class="control-label">Dimensioni borsa profondità</label>
            <input type="text" name="dimensioni_borsa_profondita" id="dimensioni_borsa_profondita" class="form-control" placeholder="Dimensioni borsa profondità"/>
        </div>
        <div class="form-group" id="dimensioni_borsa_alt_manico" style="display:none">
            <label class="control-label">Dimensioni borsa altezza manico</label>
            <input type="text" name="dimensioni_borsa_altezza_manico" id="dimensioni_borsa_altezza_manico" class="form-control" placeholder="Dimensioni borsa altezza manico"/>
        </div>
        <div class="form-group" id="dimensioni_borsa_lungh_trac" style="display:none">
            <label class="control-label">Dimensioni borsa lunghezza tracolla</label>
            <input type="text" name="dimensioni_borsa_lunghezza_tracolla" id="dimensioni_borsa_lunghezza_tracolla" class="form-control" placeholder="Dimensioni borsa lunghezza tracolla"/>
        </div>
            <div class="form-group" id="tip_accessori_donna" style="display:none">
                <label class="control-label">Tipologia accessori donna</label>
                <select data-placeholder="-- Seleziona la tipologia accessori donna --" name="tipaccessoridonna[]" class="form-control chosen-select" multiple id="tipaccessoridonna" >
                    <?php
                    for ($i=0; $i<count($tipAccessoriDonna); $i++){
                        echo "<option value=\"".$tipAccessoriDonna[$i][0]."\">".$tipAccessoriDonna[$i][1]."</option>";
                    }
                    ?>
                </select>

            </div>
        <div class="form-group" id="tip_accessori_uomo" style="display:none">
            <label class="control-label">Tipologia accessori uomo</label>
            <select data-placeholder="-- Seleziona la tipologia accessori donna --" name="tipaccessoriuomo[]" class="form-control chosen-select" multiple id="tipaccessoriuomo" >
                <?php
                for ($i=0; $i<count($tipAccessoriUomo); $i++){
                    echo "<option value=\"".$tipAccessoriUomo[$i][0]."\">".$tipAccessoriUomo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="cint_lunghezza" style="display:none">
            <label class="control-label">Cintura lunghezza</label>
            <input type="text" name="cintura_lunghezza" id="cintura_lunghezza" class="form-control" placeholder="Cintura Lunghezza"/>
        </div>
        <div class="form-group" id="cint_altezza" style="display:none">
            <label class="control-label">Cintura altezza</label>
            <input type="text" name="cintura_altezza" id="cintura_altezza" class="form-control" placeholder="Cintura altezza"/>
        </div>
        <div class="form-group" id="dim_accessorio_lungh" style="display:none">
            <label class="control-label">Dimensioni accessorio lunghezza</label>
            <input type="text" name="dimensioni_accessorio_lunghezza" id="dimensioni_accessorio_lunghezza" class="form-control" placeholder="Dimensioni accessorio lunghezza"/>
        </div>
        <div class="form-group" id="dim_accessorio_alt" style="display:none">
            <label class="control-label">Dimensioni accessorio altezza</label>
            <input type="text" name="dimensioni_accessorio_altezza" id="dimensioni_accessorio_altezza" class="form-control" placeholder="Dimensioni accessorio altezza"/>
        </div>
        <div class="form-group" id="dim_accessorio_prof" style="display:none">
            <label class="control-label">Dimensioni accessorio profondità</label>
            <input type="text" name="dimensioni_accessorio_profondita" id="dimensioni_accessorio_profondita" class="form-control" placeholder="Dimensioni accessorio profondità"/>
        </div>
        <div class="form-group" id="tip_calz_donna" style="display:none">
            <label class="control-label">Tipologia calzature donna</label>
            <select data-placeholder="-- Seleziona la tipologia calzature donna --" name="tipcalzdonna[]" class="form-control chosen-select" multiple id="tipcalzdonna" >
                <?php
                for ($i=0; $i<count($tipCalzDonna); $i++){
                    echo "<option value=\"".$tipCalzDonna[$i][0]."\">".$tipCalzDonna[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="tip_calz_uomo" style="display:none">
            <label class="control-label">Tipologia calzature uomo</label>
            <select data-placeholder="-- Seleziona la tipologia calzature uomo --" name="tipcalzuomo[]" class="form-control chosen-select" multiple id="tipcalzuomo" >
                <?php
                for ($i=0; $i<count($tipCalzUomo); $i++){
                    echo "<option value=\"".$tipCalzUomo[$i][0]."\">".$tipCalzUomo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="dim_calzatura_alt_tacco" style="display:none">
            <label class="control-label">Dimensioni calzatura altezza tacco</label>
            <input type="text" name="dimensioni_calzatura_altezza_tacco" id="dimensioni_calzatura_altezza_tacco" class="form-control" placeholder="Dimensioni calzatura altezza tacco"/>
        </div>
        <div class="form-group" id="dim_calzatura_alt_plateau" style="display:none">
            <label class="control-label">Dimensioni calzatura altezza plateau</label>
            <input type="text" name="dimensioni_calzatura_altezza_plateau" id="dimensioni_calzatura_altezza_plateau" class="form-control" placeholder="Dimensioni calzatura altezza plateau"/>
        </div>
        <div class="form-group" id="dim_calzatura_lungh_soletta" style="display:none">
            <label class="control-label">Dimensioni calzatura lunghezza soletta</label>
            <input type="text" name="dimensioni_calzatura_lunghezza_soletta" id="dimensioni_calzatura_lunghezza_soletta" class="form-control" placeholder="Dimensioni calzatura lunghezza soletta"/>
        </div>
        <div class="form-group" id="tipo_tacco_donna" style="display:none">
            <label class="control-label">Tipo tacco donna</label>
            <select data-placeholder="-- Seleziona il tipo tacco donna --" name="tipotaccodonna[]" class="form-control chosen-select" multiple id="tipotaccodonna" >
                <?php
                for ($i=0; $i<count($tipoTaccoDonna); $i++){
                    echo "<option value=\"".$tipoTaccoDonna[$i][0]."\">".$tipoTaccoDonna[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="tipo_suola" style="display:none">
            <label class="control-label">Tipo suola</label>
            <select data-placeholder="-- Seleziona il tipo suola --" name="tiposuola[]" class="form-control chosen-select" multiple id="tiposuola" >
                <?php
                for ($i=0; $i<count($tipoSuola); $i++){
                    echo "<option value=\"".$tipoSuola[$i][0]."\">".$tipoSuola[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="tipo_punta_donna" style="display:none">
            <label class="control-label">Tipo punta donna</label>
            <select data-placeholder="-- Seleziona il tipo punta donna --" name="tipopuntadonna[]" class="form-control chosen-select" multiple id="tipopuntadonna" >
                <?php
                for ($i=0; $i<count($tipoPuntaDonna); $i++){
                    echo "<option value=\"".$tipoPuntaDonna[$i][0]."\">".$tipoPuntaDonna[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="tipo_punta_uomo" style="display:none">
            <label class="control-label">Tipo punta uomo</label>
            <select data-placeholder="-- Seleziona il tipo punta uomo --" name="tipopuntauomo[]" class="form-control chosen-select" multiple id="tipopuntauomo" >
                <?php
                for ($i=0; $i<count($tipoPuntaUomo); $i++){
                    echo "<option value=\"".$tipoPuntaUomo[$i][0]."\">".$tipoPuntaUomo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_abiti" style="display:none">
            <label class="control-label">Vestibilità abiti</label>
            <select data-placeholder="-- Seleziona la vestibilità abiti --" name="vestibilitaabiti[]" class="form-control chosen-select" multiple id="vestibilitaabiti" >
                <?php
                for ($i=0; $i<count($vestibilitaAbiti); $i++){
                    echo "<option value=\"".$vestibilitaAbiti[$i][0]."\">".$vestibilitaAbiti[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_topwear" style="display:none">
            <label class="control-label">Vestibilità topwear</label>
            <select data-placeholder="-- Seleziona la vestibilità topwear --" name="vestibilitatopwear[]" class="form-control chosen-select" multiple id="vestibilitatopwear" >
                <?php
                for ($i=0; $i<count($vestibilitaTopwear); $i++){
                    echo "<option value=\"".$vestibilitaTopwear[$i][0]."\">".$vestibilitaTopwear[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_gonne" style="display:none">
            <label class="control-label">Vestibilità gonne</label>
            <select data-placeholder="-- Seleziona la vestibilità gonne --" name="vestibilitagonne[]" class="form-control chosen-select" multiple id="vestibilitagonne" >
                <?php
                for ($i=0; $i<count($vestibilitaGonne); $i++){
                    echo "<option value=\"".$vestibilitaGonne[$i][0]."\">".$vestibilitaGonne[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_pantaloni" style="display:none">
            <label class="control-label">Vestibilità pantaloni e jeans</label>
            <select data-placeholder="-- Seleziona la vestibilità pantaloni e jeans --" name="vestibilitapantaloni[]" class="form-control chosen-select" multiple id="vestibilitapantaloni" >
                <?php
                for ($i=0; $i<count($vestibilitaJeans); $i++){
                    echo "<option value=\"".$vestibilitaJeans[$i][0]."\">".$vestibilitaJeans[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_camicie_uomo" style="display:none">
            <label class="control-label">Vestibilità camicie uomo</label>
            <select data-placeholder="-- Seleziona la vestibilità camicie uomo --" name="vestcamicieuomo[]" class="form-control chosen-select" multiple id="vestcamicieuomo" >
                <?php
                for ($i=0; $i<count($vestCamicieUomo); $i++){
                    echo "<option value=\"".$vestCamicieUomo[$i][0]."\">".$vestCamicieUomo[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_camicie_donna" style="display:none">
            <label class="control-label">Vestibilità camicie donna</label>
            <select data-placeholder="-- Seleziona la vestibilità camicie donna --" name="vestcamiciedonna[]" class="form-control chosen-select" multiple id="vestcamiciedonna" >
                <?php
                for ($i=0; $i<count($vestCamicieDonna); $i++){
                    echo "<option value=\"".$vestCamicieDonna[$i][0]."\">".$vestCamicieDonna[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_giacche" style="display:none">
            <label class="control-label">Vestibilità giacche</label>
            <select data-placeholder="-- Seleziona la vestibilità giacche --" name="vestibilitagiacche[]" class="form-control chosen-select" multiple id="vestibilitagiacche" >
                <?php
                for ($i=0; $i<count($vestGiacche); $i++){
                    echo "<option value=\"".$vestGiacche[$i][0]."\">".$vestGiacche[$i][1]."</option>";
                }
                ?>
            </select>

        </div>
        <div class="form-group" id="vestibilita_capispalla" style="display:none">
            <label class="control-label">Vestibilità capispalla</label>
            <select data-placeholder="-- Seleziona la vestibilità capispalla --" name="vestibilitacapispalla[]" class="form-control chosen-select" multiple id="vestibilitacapispalla" >
                <?php
                for ($i=0; $i<count($vestCapispalla); $i++){
                    echo "<option value=\"".$vestCapispalla[$i][0]."\">".$vestCapispalla[$i][1]."</option>";
                }
                ?>
            </select>

        </div>



        <!-- Bottone per inserire le taglie e i colori -->
        <div class="form-group" style="margin-top:20px">
            <input type="button" name="inserisci_taglia_scalare" value="Inserisci taglie e scalari" class="btn btn-success active" style="height: 35px;font-size: 15px;" onclick="window.open('taglia-scalare.php','Inserisci Taglie e Scalari','scrollbars=1,width=900,height=630');"/>
        </div>




        <p style="margin-top:30px;font-size:10px;float:left;width:100%">* Campi obbligatori</p>
        <div class="form-group" style="margin-top:30px">
            <button name="salva" class="btn btn-success active" style="height: 35px;font-size: 15px;"  type="submit" onclick="controlloFormProdotto()"/>Salva</button>
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

<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
</body>
</html>