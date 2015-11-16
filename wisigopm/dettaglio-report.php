<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dettaglio Ordine | Wisigo Product Management</title>
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

    include("config/connect.php");
    $conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
    mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
    mysql_query("SET NAMES 'utf8' ");

    $id_ordine=$_REQUEST['id'];

    $ordine=Mage::getModel('sales/order')->loadByIncrementId($id_ordine);

    $livello=$_SESSION['livello'];
    $magazzino=$_SESSION['magazzino'];
    $codBoutique=$_SESSION['codBoutique'];

    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');



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
    <h1 class="page-heading">DETTAGLI ORDINE<!--<small>Sub heading here</small>--></h1>

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Dettagli ordine</h3>
        </div>
        <div class="panel-body">
            <?php
            $fromDate = $ordine->getCreatedAtStoreDate();

            $indirizzo=$ordine->getShippingAddress()->getStreet();
            $cap=$ordine->getShippingAddress()->getPostcode();
            $citta=$ordine->getShippingAddress()->getCity();
            $country=$ordine->getShippingAddress()->getCountryID();
            $stringa=$indirizzo[0]." - ".$cap." ".$citta." ".$country;
            $totale=number_format($ordine->getGrandTotal(),2,",","");
            ?>
            <p><b>Nome:</b>  <?php echo $ordine->getBillingAddress()->getFirstname() ?></p>
            <p><b>Cognome:</b>  <?php echo $ordine->getBillingAddress()->getLastname() ?><p>
            <p><b>Data ordine:</b>  <?php echo $fromDate ?><p>
            <p><b>Metodo di pagamento:</b>  <?php echo $ordine->getPayment()->getMethodInstance()->getTitle(); ?><p>
            <p><b>Metodo di spedizione:</b>  <?php echo $ordine->getShippingDescription(); ?></p>
            <p><b>Indirizzo di spedizione:</b>  <?php echo $stringa; ?></p>
            <p><b>Importo Documento:</b>  <?php echo $totale." €"; ?></p>

        </div><!-- /.panel-body -->

    </div>

    <div class="the-box full no-border">
    <div class="table-responsive">
    <table class="table table-th-block table-success">

    <?php


        ?>
        <thead>
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Scalare</th>
            <th>Stagione</th>
            <th>Genere</th>
            <th>Gruppo</th>
            <th>Descrizione Gruppo</th>
            <th>Sottogruppo</th>
            <th>Descrizione Sottogruppo</th>
            <th>Brand</th>
            <th>Descrizione Brand</th>
            <th>Listino</th>
            <th>Sconto</th>
        </tr>
        </thead>
        <tbody>
        <?php


        $_items = $ordine->getAllVisibleItems();

        $k = 1;
        foreach ($_items as $item) {
            $id_p = $item->getId();
            $sku = $item->getSku();
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

            if ($product->getTypeId() == "simple") {
                $nome = $item->getName();
                $id_product = $product->getId();
                $scalare=$product->getData("ca_scalare");
                $stagioneDB=$product->getAttributeText("ca_stagione");
                $annoDB=$product->getAttributeText("ca_anno");
                $prezzo=number_format($product->getPrice()*1.22,2,",","");


                $iva = $item->getTaxPercent();
                $prezzoI=$item->getPrice()+($item->getPrice()*$iva)/100;
                if ($iva==0) {
                    $prezzoI=$item->getPrice();
                }
                else {
                    $prezzoI=$item->getPrice()+($item->getPrice()*$iva)/100;
                }

                $sconto = number_format(($item->getDiscountAmount() * 100) / ($prezzoI), 0);
                if ($sconto==0){
                    $sconto="";
                }
                else {
                    $sconto=$sconto. "%";
                }

                // stagione
                $stringQuery = "select id_ws from " . $resource->getTableName('wsca_season') . " where LOWER(STAGIONE)='" . $stagioneDB . "' and LOWER(ANNO)='" . $annoDB . "'";
                $stagioneWS = $readConnection->fetchOne($stringQuery);

                // genere
                $categories=$product->getCategoryIds();
                $sesso=$categories[1];
                $categoria=Mage::getModel("catalog/category")->load($sesso);
                $genere=$categoria->getName();

                // gruppo e descrizione
                $gruppo=$categories[2];
                $categoria=Mage::getModel("catalog/category")->load($gruppo);
                $descrizioneGruppoWS=$categoria->getName();
                $stringQuery = "select id_ws from " . $resource->getTableName('wsca_group') . " where id_magento='" . $gruppo . "'";
                $gruppoWS = $readConnection->fetchOne($stringQuery);


                // sottogruppo e descrizione
                $sottogruppo=$categories[3];
                $categoria=Mage::getModel("catalog/category")->load($sottogruppo);
                $descrizioneSottoGruppoWS=$categoria->getName();
                $stringQuery = "select id_ws from " . $resource->getTableName('wsca_subgroup') . " where id_magento='" . $sottogruppo . "'";
                $sottogruppoWS = $readConnection->fetchOne($stringQuery);

                //brand e descrizione
                $brand=$product->getData("ca_brand");
                $descrizioneBrandWS=$product->getAttributeText("ca_brand");
                $stringQuery = "select id_ws from " . $resource->getTableName('wsca_brand') . " where id_magento='" . $brand . "'";
                $brandWS = $readConnection->fetchOne($stringQuery);

                echo "<tr>";
                echo "<td>".$sku."</td>";
                echo "<td>".$nome."</td>";
                echo "<td>".$scalare."</td>";
                echo "<td>".$stagioneWS."</td>";
                echo "<td>".$genere."</td>";
                echo "<td>".$gruppoWS."</td>";
                echo "<td>".$descrizioneGruppoWS."</td>";
                echo "<td>".$sottogruppoWS."</td>";
                echo "<td>".$descrizioneSottoGruppoWS."</td>";
                echo "<td>".$brandWS."</td>";
                echo "<td>".$descrizioneBrandWS."</td>";
                echo "<td>".$prezzo." €</td>";
                echo "<td>".$sconto."</td>";
                echo "</tr>";
            }
        }
        ?>
        </tbody>

    </table>
    <?php
        // se il livello è admin o magazzino centrale (solo per ordini da consolidare), visualizzo tutte le foto dei prodotti dell'ordine
        echo '<div style="width:100%;float:left;margin-bottom:20px">';
        foreach ($_items as $item) {
            $id_p = $item->getId();
            $sku = $item->getSku();
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
            $idProdottoConfigurabile = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
            $prodottoConfigurabile = Mage::getModel('catalog/product')->load($idProdottoConfigurabile[0]);
            $immagine=Mage::helper('catalog/image')->init($prodottoConfigurabile, 'small_image');

            echo '<img src="'.$immagine.'" style="width:25%"/>';

        }
        echo "</div>";


    ?>
    </div><!-- /.table-responsive -->
    </div>

    <div class="form-group" style="margin-top:30px">

        <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='report.php'"; ?>" />Indietro</button>

    </div>
    </div>
    <?php include("config/footer.php"); ?>
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


</html>