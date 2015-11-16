<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Elimina Prodotti | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>
    <script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text") && (node.id=="sku"))  { recuperaProdottiElimina(); return false;}
        }

        document.onkeypress = stopRKey;

    </script>

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
                <h1 class="page-heading">ELIMINA PRODOTTI<!--<small>Sub heading here</small>--></h1>
                <!-- Form inserimento prodotto -->
                <div class="the-box" style="float:left;width:100%">
                    <div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <p id="errore"></p>
                    </div>
                    <form name="form_prodotto" method="post" action="config/elimina-prodotto.php" >
                        <div id="form" style="width:100%">
                            <div class="form-group" style="float:left">
                                <label class="control-label" style="float:left;line-height:34px">Id</label>
                                <input type="text" name="sku" style="float:left;margin-left:50px;width:300px" class="form-control" placeholder="Id prodotto"  id="sku" />
                                <button name="conferma" class="btn btn-success active" style="height: 35px;font-size: 15px;float:left;margin-left:30px"  type="button" onclick="recuperaProdottiElimina()"/>Conferma</button>

                            </div>
                            <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                    <span class="sr-only">40% Complete (success)</span>
                                </div>
                            </div>
                            <div id="tab_prodotti" style="float:left;margin-top:20px;width:100%;display:none">

                            </div>

                            <div id="bottoni" style="display:none">
                                <div class="form-group" style="margin-top:10px;float:left">
                                    <button type="button" id="selezionaTutto" name="seleziona" class="btn btn-success active" style="height: 28px;font-size: 12px;" onclick="selezionaTuttoElimina()" >Seleziona tutto</button>
                                    <button type="button" id="deselezionaTutto" name="deseleziona"  class="btn btn-success active" style="height: 28px;font-size: 12px;" onclick="deselezionaTuttoElimina()" >Deseleziona tutto</button>
                                </div>
                                <p style="margin-top:30px;font-size:10px;float:left;width:100%">* Campi obbligatori</p>
                                <div class="form-group" style="margin-top:30px;float:left">
                                    <button type="button" id="elimina" name="elimina" class="btn btn-success active" style="height: 35px;font-size: 15px;" onclick="controlloFormEliminaProdotto()" >Elimina</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php  include("config/footer.php") ?>
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