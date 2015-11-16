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
<body class="tooltips" onload="window.resizeTo('900','750')" style="padding-top:0;background-color:#E8E9EE">
<?php
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        // recupero tutte le taglie inserite precedentemente e gli scalari
        $taglia=$_REQUEST['taglia'];
        $scalare=$_REQUEST['scalare'];



        // azzero le variaibili di sessione e le riassegno ai valori di taglia inseriti precedentemente
        unset($_SESSION['taglie_scelte']);
        unset($_SESSION['scalari_scelti']);
        $_SESSION['taglie_scelte']=$taglia;
        $_SESSION['scalari_scelti']=$scalare;



        for ($i=0; $i<count($taglia); $i++){
            $pos=strpos($taglia[$i], "\\");
            $id_taglia[$i]=substr($taglia[$i],0,$pos);
            $nome_taglia[$i]=substr($taglia[$i],$pos+1,strlen($taglia[$i])-$pos);
        }

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
                        <form name="form_tc" method="post" action="config/taglia-scalare.php" onsubmit="return controlloFormQta()">
                            <div id="form" style="width:822px">
                                <h4 class="small-title" style="font-size:18px"><b>Inserisci le quantità per ogni prodotto:</b></h4>
                                <?php
                                echo "<table class=\"table table-th-block tabella_tc\" cellspacing=20>";
                                for ($i=0; $i<count($taglia); $i++){
                                    echo "<tr>";
                                    echo"<td><label class=\"label2\"><b>Prodotto ".($i+1)." *</b></label></td>
								<td><label class=\"label2\">Taglia: $nome_taglia[$i]</label></td>
								<td><label class=\"label2\">Scalare: $scalare[$i]</label></td>
								<td><input type=\"text\" name=\"qta[]\" class=\"form-control\" style=\"width:50px;margin-top:0;\" ";
                                    if (isset($_SESSION['qta'][$i])){
                                        echo " value=\"".$_SESSION['qta'][$i]."\" ";
                                    }
                                    echo "/></td>
								</tr>";

                                }
                                echo "</table>";
                                ?>


                                <p style="font-size:12px;float:left;width:100%;margin-top:30px;">* Campi obbligatori</p>

                                <div class="riga" style="margin-top:30px;float:left;width:100%">
                                                <button type="submit" name="conferma" class="btn btn-success active" style="height: 35px;font-size: 15px;" >Conferma</button>
                                </div>


                                 <div style="float:left;width:100%">
                                      <button type="button" id="generaEtichetta" name="indietro"  class="btn btn-danger active" style="height: 35px;font-size: 15px;margin-top:30px" onclick="window.location='taglia-scalare.php'" >Indietro</button>
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