<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inserisci Geopricing | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==0 && $_SESSION['username']=="coltorti") {

        include("config/percorsoMage.php");
        require_once $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        require('config/sorter.php');

        // recupero le opzioni dell'attributo tipovendita
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

        // recupero le opzioni dell'attributo codice_stagione
        $attribute_model = Mage::getModel('eav/entity_attribute');
        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_stagione");
        $attribute = $attribute_model->load($attribute_code);

        $attribute_options_model->setAttribute($attribute);
        $options = $attribute_options_model->getAllOptions(false);

        $i=0;
        foreach($options as $option) {
            $stagione[$i][0]=$option['value'];
            $stagione[$i][1]=$option['label'];
            $i=$i+1;
        }




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
        <h1 class="page-heading">INSERISCI GEOPRICING<!--<small>Sub heading here</small>--></h1>
        <!-- Form inserimento prodotto -->
        <div class="the-box">
        <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                <span class="sr-only">40% Complete (success)</span>
            </div>
        </div>
        <form id="inserisci_geo"  name="form_geo" method="post" action="config/inserisci-geopricing.php" >
        <!--<h4 class="small-title">FORM PER L'INSERIMENTO DI UN NUOVO PRODOTTO</h4>-->
        <div class="form-group">
            <label class="control-label">Store *</label>
            <select name="store" class="form-control" id="store" >
                <option value="">-- Seleziona lo store --</option>
            <?php
                        foreach (Mage::app()->getWebsites() as $website) {
                            foreach ($website->getGroups() as $group) {
                                echo "<option value=\"" . $group->getId() . "\">" . $group->getName() . "</option>";
                            }
                        }
            ?>
            </select>
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
            <select name="anno" class="form-control" id="anno" >
                <option value="">-- Seleziona l'anno --</option>
                <?php
                $anno=date("Y");
                for ($i=0; $i<=100; $i++){
                    echo "<option value=".$anno.">".$anno."</option>";
                    $anno=$anno+1;
                }
                ?>
            </select>

        </div>
            <div class="form-group">
                <label class="control-label">Mark up *</label>
                <input type="text" name="markup" id="markup" class="form-control" placeholder="Mark up"/>

            </div>



        <p style="margin-top:30px;font-size:10px;float:left;width:100%">* Campi obbligatori</p>
        <div class="form-group" style="margin-top:30px">
            <button name="salva" class="btn btn-success active" style="height: 35px;font-size: 15px;"  type="submit" onclick="controlloFormGeo()" />Salva</button>
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
<script src="//oss.maxcdn.com/momentjs/2.8.2/moment.min.js"></script>
<script src="assets/plugins/validator/bootstrapValidator.js"></script>



<script src="assets/plugins/validator/geopricing.js"></script>


<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>
</body>
</html>

<script>
    /* inserisci geopricing */

    function controlloFormGeo(){
        document.getElementById('loading').style.display="block";
        document.forms['form_geo'].submit();
        document.body.scrollTop = document.documentElement.scrollTop = 0;


    }
</script>