<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lista Ordini | Wisigo Product Management</title>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->

    <link href="assets/plugins/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']!=2) {

        include("config/percorsoMage.php");
        require_once $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter(
                array(
                    'status',
                    'status'
                ),
                array(
                    array('eq' => 'complete'),
                    array('eq' => 'processing')
                )
            )
            ->setOrder('increment_id', 'desc')
        ;





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
                    <h1 class="page-heading">ORDINI COMPLETI<!--<small>Sub heading here</small>--></h1>


                    <div class="the-box">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-th-block table-success" id="datatable-order">
                                <thead class="the-box dark full">
                                <tr>
                                    <th style="display:none"></th>
                                    <th></th>
                                    <th style="text-align:left;padding-left:8px">N°</th>
                                    <th style="text-align:left;padding-left:8px">Acquistato il</th>
                                    <th style="text-align:left;padding-left:8px">Azioni</th>
                                    <th style="text-align:left;padding-left:8px">Spedisci al nome</th>
                                    <th style="text-align:left;padding-left:8px">Stato</th>
                                    <th style="text-align:left;padding-left:8px">Tipologia</th>
                                    <th style="text-align:left;padding-left:8px">Provenienza</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=0;
                                foreach ($orders as $order) {
                                    $id=$order->getIncrementId();
                                    $fromDate = $order->getCreatedAtStoreDate();
                                    $stato=$order->getStatus();

                                    $dataOrdine=$fromDate->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
                                    $time=strtotime($dataOrdine);
                                    $mese=date("m",$time);
                                    $anno=date("Y",$time);
                                    $giorno=date("d",$time);
                                    $dateOrder = $anno."".$mese."".$giorno;

                                    $nomeFattura=$order->getBillingAddress()->getFirstname();
                                    $cognomeFattura=$order->getBillingAddress()->getLastname();
                                    $nomeSpedisci=$order->getShippingAddress()->getFirstname();
                                    $cognomeSpedisci=$order->getShippingAddress()->getLastname();
                                    echo "<tr>";
                                    echo "<td style=\"display:none\">".$dateOrder."</td>";
                                    echo "<td>immagine</td>";
                                    echo "<td style=\"text-align:left\">".$id."</td>";
                                    echo "<td style=\"text-align:left\">".$fromDate."</td>";
                                    if ($stato=="complete") {
                                        $shipment = $order->getShipmentsCollection()->getFirstItem();
                                        $labelContent = $shipment->getShippingLabel();
                                        if ($labelContent!=null) {
                                            echo "<form name='form_scarica' method='post' action='config/scarica-label.php'>";
                                            echo "<input type=\"hidden\" name=\"order_id\" value=\"" . $id . "\" />";
                                            echo "<td><button type=\"submit\" name=\"spedisci\" class=\"btn btn-success active\" value=\"Scarica PDF\">Scarica PDF</button>";
                                            echo "<button type=\"button\" onclick=\"window.open('assistenza-clienti.php','Assistenza Clienti','width=1170,height=570');\" style='margin-left:10px' name=\"assistenza\" class=\"btn btn-success active\" value=\"Assistenza\">Assistenza</button>";
                                            echo "</td>";
                                            echo "</form>";
                                        }
                                        else {
                                            echo "<td><button type=\"button\" disabled name=\"spedisci\" class=\"btn btn-success active\" value=\"Scarica PDF\">Scarica PDF</button>";
                                            echo "<button type=\"button\" onclick=\"window.open('assistenza-clienti.php','Assistenza Clienti','width=1170,height=570');\" style='margin-left:10px' name=\"assistenza\" class=\"btn btn-success active\" value=\"Assistenza\">Assistenza</button>";
                                            echo "</td>";
                                        }
                                        echo "<td style=\"text-align:left\">".$cognomeSpedisci." ".$nomeSpedisci."</td>";
                                        echo "<td style=\"text-align:left\"><span class=\"label label-success\">Completo</span>";
                                    }
                                    else if ($stato=="processing"){
                                        echo "<form name='form_dettaglio' method='post' action='dettaglio-spedizione.php'>";
                                        echo "<input type=\"hidden\" name=\"order_id\" value=\"".$id."\" />";
                                        echo "<td><button type=\"submit\" name=\"spedisci\" class=\"btn btn-success active\" value=\"Spedisci\">Spedisci</button>";
                                        echo "<button type=\"button\" onclick=\"window.open('assistenza-clienti.php','Assistenza Clienti','width=1170,height=570');\" style='margin-left:10px' name=\"assistenza\" class=\"btn btn-success active\" value=\"Assistenza\">Assistenza</button>";
                                        echo "</td>";
                                        echo "</form>";
                                        echo "<td style=\"text-align:left\">".$cognomeSpedisci." ".$nomeSpedisci."</td>";
                                        echo "<td style=\"text-align:left\"><span class=\"label label-warning\">Da Spedire</span>";
                                    }


                                    if ($i%2==0) {
                                        echo "<td style=\"text-align:left\"><span class=\"label label-warning\">Ordine Multiplo</span>";
                                    }
                                    else {
                                        echo "<td style=\"text-align:left\"><span class=\"label label-success\">Ordine Singolo</span>";
                                    }
                                    if ($i==1){
                                        echo "<td style=\"text-align:left\"><span class=\"label label-info\">Farfetch</span>";
                                    }
                                    else if ($i%2==0) {
                                        echo "<td style=\"text-align:left\"><span class=\"label label-danger\">Ecommerce</span>";
                                    }
                                    else {
                                        echo "<td style=\"text-align:left\"><span class=\"label label-primary\">Portale</span>";
                                    }
                                    echo "<td style=\"text-align:left\"><a href=\"dettaglio-ordine.php?id=$id\">Dettaglio ordine</a></td>";


                                    echo "</tr>";
                                    $i=$i+1;
                                }
                                ?>

                                </tbody>

                            </table>
                        </div>
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
<script src="assets/plugins/datatable/js/jquery.dataTables.js"></script>
<script src="assets/plugins/datatable/js/bootstrap.datatable.js"></script>
<script src="assets/plugins/slider/bootstrap-slider.js"></script>



<!-- MAIN APPS JS -->
<script src="assets/js/tabella.js"></script>
<script src="assets/js/apps.js"></script>
</html>