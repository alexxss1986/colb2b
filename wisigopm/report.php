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


    include("config/percorsoMage.php");
    require_once $MAGE;
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

    include("config/connect.php");
    $conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
    mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
    mysql_query("SET NAMES 'utf8' ");

// recupero variabili di sessione
    $livello=$_SESSION['livello'];
    $magazzino=$_SESSION['magazzino'];
    $codBoutique=$_SESSION['codBoutique'];


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
            ->setOrder('increment_id', 'desc');





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
        <a href="config/esportaExcel.php" style="margin-bottom: 20px;float: left;width: 100%;">Esporta Excel</a>
    <div class="table-responsive">
    <table class="table table-striped table-hover table-th-block table-success" id="datatable-order">
    <thead class="the-box dark full">
    <tr>
        <th style="display:none"></th>
        <th style="text-align:left;padding-left:8px">N°</th>
        <th style="text-align:left;padding-left:8px">Acquistato il</th>
        <th style="text-align:left;padding-left:8px">Spedisci al nome</th>
        <th style="text-align:left;padding-left:8px">Tipologia</th>
        <th style="text-align:left;padding-left:8px">Provenienza</th>
        <th style="text-align:left;padding-left:8px">Importo</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php
    // recupero tutti i magazzini salvati
    $warehouses = Mage::getModel('wgmulti/warehouse')
        ->getCollection()
        ->addOrder('position', 'ASC');
    $warehouseData = $warehouses->toFlatArray();

        // livello magazzino centrale
        $i = 0;
        foreach ($orders as $order) {
            // recuero dati ordine
            $id_ordine = $order->getIncrementId();
            $fromDate = $order->getCreatedAtStoreDate();
            $stato = $order->getStatus();
            $totale=number_format($order->getGrandTotal(),2,",","");

            $dataOrdine = $fromDate->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
            $time = strtotime($dataOrdine);
            $mese = date("m", $time);
            $anno = date("Y", $time);
            $giorno = date("d", $time);
            $dateOrder = $anno . "" . $mese . "" . $giorno;

            $nomeFattura = $order->getBillingAddress()->getFirstname();
            $cognomeFattura = $order->getBillingAddress()->getLastname();
            $nomeSpedisci = $order->getShippingAddress()->getFirstname();
            $cognomeSpedisci = $order->getShippingAddress()->getLastname();

            // recupero tutti i magazzini dove i prodotti sono stati ordinati
            $magazzinoArray = array();
            $_items = $order->getAllItems();
            foreach ($_items as $item) {

                $serializedAdditionalData = $item->getAdditionalData();
                if (empty($serializedAdditionalData)) {

                } else {
                    $additionalData = unserialize($serializedAdditionalData);


                    foreach ($warehouseData as $wid => $wdata) {
                        if (array_key_exists($wid, $additionalData)) {
                            $qty = $additionalData[$wid];
                            if ($qty > 0) {
                                $magazzinoArray[$i] = $wid;
                                $i = $i + 1;
                            }
                        }
                    }
                }

            }

            // elimino i duplicati per sapere quanti magazzini sono stati utilizzati
            $magazzinoArray = array_unique($magazzinoArray);
            $magazzinoArray = array_values($magazzinoArray);

            if (count($magazzinoArray) > 1) {
                // se ci sono più magazzini allora avremo un ordine consolidato
                if ($stato == "complete") {
                    echo "<tr style='background:#a1d4e7'>";
                }
                else {
                    echo "<tr>";
                }
                echo "<td style=\"display:none\">" . $dateOrder . "</td>";
                echo "<td style=\"text-align:left\">" . $id_ordine . "</td>";
                echo "<td style=\"text-align:left\">" . $fromDate . "</td>";
                if ($stato == "complete") {

                    echo "<td style=\"text-align:left\">" . $cognomeSpedisci . " " . $nomeSpedisci . "</td>";

                    echo "<td style=\"text-align:left\"><span class=\"label label-warning\">Ordine consolidato</span></td>";

                } else if ($stato == "processing") {

                    echo "<td style=\"text-align:left\">" . $cognomeSpedisci . " " . $nomeSpedisci . "</td>";

                    echo "<td style=\"text-align:left\"><span class=\"label label-warning\">Ordine da Consolidare</span></td>";

                }

                echo "<td style=\"text-align:left\"><span class=\"label label-danger\">Ecommerce</span></td>";

                echo "<td style=\"text-align:left\">".$totale." €</td>";

                echo "<td style=\"text-align:left\"><a href=\"dettaglio-report.php?id=$id_ordine\">Dettaglio ordine</a></td>";

                echo "</tr>";
            }
            else {
                // esiste un solo magazzino e quindi abbiamo un ordine singolo
                // controllo se l'ordine contiene prodotti provenienti dal magazzino centrale

                // se è presente almeno un prodotto visualizzo l'ordine
                if ($stato == "complete") {
                    echo "<tr style='background:#a1d4e7'>";
                }
                else {
                    echo "<tr>";
                }
                echo "<td style=\"display:none\">" . $dateOrder . "</td>";
                echo "<td style=\"text-align:left\">" . $id_ordine . "</td>";
                echo "<td style=\"text-align:left\">" . $fromDate . "</td>";
                if ($stato == "complete") {
                    echo "<td style=\"text-align:left\">" . $cognomeSpedisci . " " . $nomeSpedisci . "</td>";

                    echo "<td style=\"text-align:left\"><span class=\"label label-success\">Ordine spedito</span></td>";

                } else if ($stato == "processing") {

                    echo "<td style=\"text-align:left\">" . $cognomeSpedisci . " " . $nomeSpedisci . "</td>";
                    echo "<td style=\"text-align:left\"><span class=\"label label-success\">Ordine da Spedire</span></td>";

                }

                echo "<td style=\"text-align:left\"><span class=\"label label-danger\">Ecommerce</span></td>";

                echo "<td style=\"text-align:left\">".$totale." €</td>";

                echo "<td style=\"text-align:left\"><a href=\"dettaglio-report.php?id=$id_ordine\">Dettaglio ordine</a></td>";

                echo "</tr>";
            }




            $i = $i + 1;
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