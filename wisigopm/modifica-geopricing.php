<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Modifica geopricing | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>


    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){
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
                <h1 class="page-heading">MODIFICA GEOPRICNG<!--<small>Sub heading here</small>--></h1>
                <!-- Form inserimento prodotto -->
                <div class="the-box" style="float:left;width:100%">
                    <div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p id="errore"></p>
                    </div>
                    <form name="form_geo" method="post" action="visualizza-geopricing.php" onsubmit="return controlloFormModificaGeo()">
                        <div id="form" style="width:100%">
                            <div class="form-group">
                                <label class="control-label">Store</label>
                                <select name="store" class="form-control" id="store" >
                                    <option value="">-- Seleziona lo store --</option>
                                    <?php
                                    foreach (Mage::app()->getWebsites() as $website) {
                                        foreach ($website->getGroups() as $group) {
                                            echo "<option value=\"" . $group->getId() . "\"";
                                            if (isset($_REQUEST['store']) && $_REQUEST['store']==$group->getId()){
                                                echo " selected ";
                                            }
                                            echo ">" . $group->getName() . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="form-group">
                                <label class="control-label">Brand</label>
                                <select name="brand" class="form-control" id="brand" >
                                    <option value="">-- Seleziona il brand --</option>
                                    <?php
                                    for ($i=0; $i<count($brand); $i++){
                                        echo "<option value=\"".$brand[$i][0]."\"";
                                        if (isset($_REQUEST['brand']) && $_REQUEST['brand']==$brand[$i][0]){
                                            echo " selected ";
                                        }
                                        echo ">".$brand[$i][1]."</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label class="control-label">Stagione</label>
                                <select name="stagione" class="form-control" id="stagione">
                                    <option value="">-- Seleziona la stagione --</option>
                                    <?php
                                    for ($i=0; $i<count($stagione); $i++){
                                        echo "<option value=\"".$stagione[$i][0]."\"";
                                        if (isset($_REQUEST['stagione']) && $_REQUEST['stagione']==$stagione[$i][0]){
                                            echo " selected ";
                                        }
                                        echo ">".$stagione[$i][1]."</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label class="control-label">Anno</label>
                                <select name="anno" class="form-control" id="anno" >
                                    <option value="">-- Seleziona l'anno --</option>
                                    <?php
                                    $anno=date("Y");
                                    for ($i=0; $i<=100; $i++){
                                        echo "<option value=".$anno."";
                                        if (isset($_REQUEST['anno']) && $_REQUEST['anno']==$anno){
                                            echo " selected ";
                                        }
                                        echo ">".$anno."</option>";
                                        $anno=$anno+1;
                                    }
                                    ?>
                                </select>

                            </div>
                            <div class="form-group" style="float:left">
                                <button name="conferma" class="btn btn-success active" style="height: 35px;font-size: 15px;float:left;"  type="button" onclick="recuperaGeoVisualizza()"/>Conferma</button>
                            </div>
                            <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                            <?php

                                echo "<div id=\"tab_geo\" style=\"float:left;margin-top:20px;width:100%;display:none\">
            	<div class=\"the-box full no-border\">
						<div class=\"table-responsive\">";

                            ?>
                        </div>
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
<script src="assets/plugins/slider/bootstrap-slider.js"></script>



<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>
</body>
</html>


<script>
    /* modifica-geopricing.php */
    function recuperaGeoVisualizza() {
        store=document.getElementById('store').value;
        brand=document.getElementById('brand').value;
        anno=document.getElementById('anno').value;
        stagione=document.getElementById('stagione').value;

            document.getElementById('loading').style.display='block';
            var url = "config/recuperaGeo.php?store="+store+"&brand="+brand+"&anno="+anno+"&stagione="+stagione;
            XMLHTTP = RicavaBrowser(recuperaGeoVisualizza2);
            XMLHTTP.open("GET", url, true);
            XMLHTTP.send(null);

    }

    function recuperaGeoVisualizza2(){
        if (XMLHTTP.readyState == 4)
        {
            array=XMLHTTP.responseText;
            array=eval(array);
            document.getElementById('loading').style.display='none';
            if (array[0][0]=="0"){
                document.getElementById('tab_geo').innerHTML="";
                document.getElementById('tab_geo').style.display='none';
                document.getElementById('riga_errore').style.display='block';
                document.getElementById('errore').innerHTML=array[0][1]+'!';
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            }
            else {
                stringa="<div class=\"the-box full no-border\"><div class=\"table-responsive\"><table class=\"table table-th-block table-success\" style=\"width:630px\" cellpadding=5><thead><tr><th style=\"text-align:left\">Store</th><th style=\"text-align:left\">Brand</th><th style=\"text-align:left\">Stagione</th><th style=\"text-align:left\">Anno</th><th style=\"text-align:left\">Mark up</th></thead></tr><tbody>";
                for (i=0; i<array.length; i++){
                    stringa+="<tr><td align=left>"+array[i][1]+"</td><td align=left>"+array[i][2]+"</td><td align=left>"+array[i][3]+"</td><td align=left>"+array[i][4]+"</td><td align=left>"+array[i][5]+"</td></tr>";
                }
                stringa+="</tbody></table></div></div>";
                document.getElementById('tab_geo').style.display='block';
                document.getElementById('tab_geo').innerHTML=stringa;
            }
        }
    }


    function controlloFormModificaProdotto(){
        errore="";
        flag=true;
        check=document.getElementsByName('check');
        flag2=false;
        for (i=0; i<check.length; i++){
            if (check[i].checked==true){
                flag2=true;
            }
        }

        if (flag2==false){
            errore+="Selezionare almeno un geopricing!";
            flag=false;
        }

        if (flag==false){
            document.getElementById('riga_errore').style.display="block";
            document.getElementById('errore').innerHTML=errore;
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        }


        return flag;
    }
</script>