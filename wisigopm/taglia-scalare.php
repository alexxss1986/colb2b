<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inserisci taglie e scalari | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>
    <script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text") && (node.id=="prodotti"))  { caricaAttributi(); return false;}
        }

        document.onkeypress = stopRKey;

    </script>

    <link href="css/style.css" rel="stylesheet">

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips" onload="window.resizeTo('900','630')" style="padding-top:0;background-color:#E8E9EE">
<?php

if (isset($_SESSION['username'])){

if ($_SESSION['livello']==3) {

include("config/percorsoMage.php");
require_once $MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

require('config/sorter.php');
$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');

// recupero tutte le taglie in magento
$scalare=$_SESSION['scalare'];


// controllo se ho già inserito dei prodotti semplici con taglia e scalare
// se ci sono recupero gli id delle taglie
if (isset($_SESSION['scalari_s'])){
    $scalariscelti=$_SESSION['scalari_s'];

}


if (isset($_SESSION['taglie_s'])){
    $tagliescelte=$_SESSION['taglie_s'];
    for ($i=0; $i<count($tagliescelte); $i++){
        $pos=strpos($tagliescelte[$i], "\\");
        $id_tagliescelte[$i]=substr($tagliescelte[$i],0,$pos);
    }
}



?>
<?php
if (isset($_SESSION['taglie_s'])){
?>
<!--
    ===========================================================
    BEGIN PAGE
    ===========================================================
    -->
<div class="wrapper">

<!-- BEGIN PAGE CONTENT -->
<div class="page-content" style="margin-left:0">
<div class="container-fluid">

<!-- Begin page heading -->
<h1 class="page-heading">INSERISCI TAGLIE E SCALARI<!--<small>Sub heading here</small>--></h1>
<!-- Form inserimento prodotto -->
<div class="the-box" style="float:left;width:100%">
<div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <p id="errore"></p>
</div>
<div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
        <span class="sr-only">40% Complete (success)</span>
    </div>
</div>
<form name="form_tc" method="post" action="tabella_taglia_scalare.php" onsubmit="return controlloFormTagliaScalare()">
    <div id="form" style="width:720px">
        <div class="form-group" style="float:left">
            <label class="control-label" style="float:left;line-height:34px">Numero prodotti da inserire</label>
            <input type="text" name="prodotti" id="prodotti" class="form-control" style="margin-left:50px;width:250px;margin-top:0;float:left"/>
            <button type="button"  class="btn btn-success active" name="conferma" style="height: 30px;font-size: 15px;margin-left:30px;float:left" onclick="caricaAttributi()" >Conferma</button>
        </div>
        <div id="riga_attr">
            <?php
            // costruisco la tabella contenente tutti i prodotti inseriti con taglia e scalare scelti
            echo "<table class=\"table table-th-block table-success\" id=\"tabella_prodotti\" cellpadding=8><thead><tr><th></th><th style=\"text-align:center\" width=154>Scalare *</th><th style=\"text-align:center\" width=154>Taglia *</th><th></th></thead></tr><tbody>";
            for ($i=0; $i<count($tagliescelte); $i++){
                echo "<tr><td><label class=\"control-label\"><b>Prodotto ".($i+1)." *</b></label>
                <td><select style='width: 270px;' name=\"scalare[]\" class=\"input-text select_attr\"><option value=\"\">...</option>";
                for ($j=0; $j<count($scalare); $j++){
                    echo "<option value=\"".$scalare[$j][0]."\"";

                    if ($scalare[$j][0]==$scalariscelti[$i]){
                        echo " selected";
                    }

                    echo ">".$scalare[$j][1]."</option>";
                }
                echo "</select></td>";

                $taglia=array();
                $stringQuery = "select id_taglia,taglia from " . $resource->getTableName('scalariusa_taglie') . " where id_scalare='".$scalariscelti[$i]."'";
                $listaTaglie = $readConnection->fetchAll($stringQuery);
                $j=0;
                foreach ($listaTaglie as $row) {
                    $taglia[$j][0] = $row['id_taglia'];
                    $taglia[$j][1] = $row['taglia'];
                    $j=$j+1;
                }



                echo "<td><select name=\"taglia[]\" class=\"input-text select_attr\"><option value=\"\">...</option>";
                for ($j=0; $j<count($taglia); $j++){
                    echo "<option value=\"".$taglia[$j][0]."\\".$taglia[$j][1]."\"";

                    if ($taglia[$j][0]==$id_tagliescelte[$i]){
                        echo " selected";
                    }

                    echo ">".$taglia[$j][1]."</option>";
                }
                echo "</select></td><td><img class='img_delete' src='img/delete.png' width=32 onclick=\"deleteRow(this)\" /></td></tr>";
            }
            echo "</tbody></table>";

            ?>
        </div>

        <div id="bottoni_attr" style="float:left; margin-top:30px;display:block;width:100%">

            <p style="font-size:12px;float:left">* Campi obbligatori</p>

            <div class="riga" style="margin-top:20px;float:left;width:100%">
                <button type="submit" name="avanti"  class="btn btn-success active" style="height: 35px;font-size: 15px;" >Avanti</button>
            </div>
        </div>
        <div class="riga" style="float:left;width:100%">
            <button type="button" id="indietro" name="indietro"  class="btn btn-danger active" style="height: 35px;font-size: 15px;margin-top:30px" onclick="self.close()" >Esci</button>
        </div>
    </div>
</form>
<?php
}
else {
    ?>

    <!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
    <div class="wrapper">

        <!-- BEGIN PAGE CONTENT -->
        <div class="page-content" style="margin-left:0">
            <div class="container-fluid">

                <!-- Begin page heading -->
                <h1 class="page-heading">INSERISCI TAGLIE E SCALARI<!--<small>Sub heading here</small>--></h1>
                <!-- Form inserimento prodotto -->
                <div class="the-box" style="float:left;width:100%">
                    <div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p id="errore"></p>
                    </div>
                    <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                            <span class="sr-only">40% Complete (success)</span>
                        </div>
                    </div>
                    <form name="form_tc" method="post" action="tabella_taglia_scalare.php" onsubmit="return controlloFormTagliaScalare()">
                        <div id="form" style="width:720px">
                            <div class="form-group" style="float:left">
                                <label class="control-label" style="float:left;line-height:34px">Numero prodotti da inserire</label>
                                <input type="text" name="prodotti" id="prodotti" class="form-control" style="float:left;margin-left:50px;width:250px"/>
                                <button type="button" name="conferma" class="btn btn-success active" style="height: 35px;font-size: 15px;margin-left:30px;float:left" onclick="caricaAttributi()" >Conferma</button>
                            </div>
                            <div id="riga_attr">

                            </div>


                            <div id="bottoni_attr" style="float:left; margin-top:30px;display:none;width:100%">

                                <p style="font-size:12px;float:left">* Campi obbligatori</p>

                                <div class="form-group" style="margin-top:20px;float: left;
width: 100%;">
                                    <button type="submit" name="avanti" class="btn btn-success active" style="height: 35px;font-size: 15px;" >Avanti</button>
                                </div>
                            </div>
                            <div style="float:left;width:100%">
                                <button type="button" id="indietro"  class="btn btn-danger active" name="indietro" class="form-button" style="height: 35px;font-size: 15px;margin-top:30px" onclick="self.close()" >Esci</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php include("config/footer.php") ?>
        </div>
    </div>

<?php 					}
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
<script src="assets/plugins/slider/bootstrap-slider.js"></script>

<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>

</body>
</html>