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

    if ($_SESSION['livello']==0 && $_SESSION['username']=='coltorti'){
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
                ?>
                <p><b>Nome:</b>  <?php echo $ordine->getBillingAddress()->getFirstname() ?></p>
                <p><b>Cognome:</b>  <?php echo $ordine->getBillingAddress()->getLastname() ?><p>
                <p><b>Data ordine:</b>  <?php echo $fromDate ?><p>
                <p><b>Metodo di pagamento:</b>  <?php echo $ordine->getPayment()->getMethodInstance()->getTitle(); ?><p>
                <p><b>Metodo di spedizione:</b>  <?php echo $ordine->getShippingDescription(); ?></p>
                <p><b>Indirizzo di spedizione:</b>  <?php echo $stringa; ?></p>

            </div><!-- /.panel-body -->

        </div>

        <div class="the-box full no-border">
            <div class="table-responsive">
                <form name="form_magazzino" method="post" action="config/sposta-magazzini.php" >
                    <table class="table table-th-block table-success">

                        <?php

                        $warehouses = Mage::getModel('wgmulti/warehouse')
                            ->getCollection()
                            ->addOrder('position', 'ASC');
                        $warehouseData = $warehouses->toFlatArray();

                        $i=0;
                        $magazzinoArray = array();
                        $qtyArray = array();
                        foreach ($warehouseData as $wid => $wdata) {
                                            $magazzinoArray[$i] = $wid;
                                            $i = $i + 1;
                        }


                        $magazzinoArray = array_unique($magazzinoArray);
                        $magazzinoArray = array_values($magazzinoArray);


                        ?>
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Id</th>
                            <th>PRINC</th>
                            <th>BMDB</th>
                            <th>BJES</th>
                            <th>BANC</th>
                            <th>BMAC</th>
                            <th>BSBN</th>
                            <th>BPESC</th>
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

                                /*
                                 * 1 = Jesi
                                 * 2 = San benedetto
                                 * 3 = Macerata
                                 * 4 = Ancona
                                 * 5 = BMDB
                                 * 6 = PRINC
                                 * 8 = PESC
                                 */
                                $qtyArray = array("6" => "0", "5" => "0", "1" => "0", "4" => "0", "3" => "0", "2" => "0", "8" => "0");

                                echo "<tr>";
                                echo "<td>" . $nome . "</td>";
                                echo "<td>" . $sku . "</td>";
                                for ($i = 0; $i < count($magazzinoArray); $i++) {

                                            $indice = $magazzinoArray[$i];
                                            $qtyArray[$indice] = 0;

                                }




                                $r=0;
                                foreach ($qtyArray as $indice=>$qty) {
                                    $nome_qty="qty_".$indice."[]";
                                    echo "<td><input class='qty_mag' style='width:50px;text-align: center' type='text' name='".$nome_qty."' value='" . number_format($qty, 0, "", "") . "' /></td>";
                                }



                                $k = $k + 1;
                            }
                        }
                        echo "<input type='hidden' name='id_ordine' value='".$id_ordine."' />";
                        ?>
                        </tbody>

                    </table>
                    <div style="float:left;width:100%">
                        <button name="sposta" class="btn btn-success active" style="height: 35px;font-size: 15px;margin-bottom:30px;"  type="submit" />Crea magazzini</button>
                    </div>
                </form>
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

            <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='ordini.php'"; ?>" />Indietro</button>

        </div>
        </div>
        <?php include("config/footer.php"); ?>
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

<script>
    jQuery(document).on("keyup",".qty_mag",function(){
        valore=jQuery(this).val();
        if (isNaN(valore)){
            alert("Formato quantità non corretto!");
            jQuery(this).val(0);
        }
        else if (String(valore).indexOf(".") != (-1)) {
            alert("Formato quantità non corretto!");
            jQuery(this).val(0);
        }
        else if (String(valore).indexOf(",") != (-1)) {
            alert("Formato quantità non corretto!");
            jQuery(this).val(0);
        }
        else if (valore < 0){
            alert("Formato quantità non corretto!");
            jQuery(this).val(0);
        }
    });


</script>
</html>