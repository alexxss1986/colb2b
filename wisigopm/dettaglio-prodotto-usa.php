<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dettaglio prodotto | Wisigo Product Management</title>
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
    if ($_SESSION['livello']==0) {

        include("config/connect.php");
        $conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
        mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");

        include("config/percorsoMage.php");
        require_once $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $id=$_REQUEST['id'];

        $config = Mage::getModel('catalog/product')->load($id);
        $collection = $config->getTypeInstance()->getUsedProductIds();

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
                    <h1 class="page-heading">PRODOTTI SEMPLICI<!--<small>Sub heading here</small>--></h1>

                    <div class="the-box full no-border">
                        <div class="table-responsive">
                            <table class="table table-th-block table-success">
                                <thead>
                                <tr>
                                    <th style="display:none">ID</th>
                                    <th style="text-align:left">SKU</th>
                                    <th style="text-align:left">Nome</th>
                                    <th style="text-align:left">Brand</th>
                                    <th style="text-align:left">Taglia</th>
                                    <th style="text-align:left">Colore</th>
                                    <th style="text-align:left">Quantità</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($collection as $id_product) {
                                    $prodotto=Mage::getModel('catalog/product')->load($id_product);
                                    $sku=$prodotto->getSku();
                                    $nome=$prodotto->getName();
                                    $brand=$prodotto->getAttributeText("ca_brand");
                                    $qta=Mage::getModel('cataloginventory/stock_item')->loadByProduct($prodotto)->getQty();
                                    $taglia=$prodotto->getAttributeText("ca_misura");
                                    $colore=$prodotto->getAttributeText("ca_colore");


                                    echo "
										<tr>
											<td style=\"display:none\">".$prodotto->getId()."</td>
											<td style=\"text-align:left\">".$sku."</td>
											<td style=\"text-align:left\">".$nome."</td>
											<td style=\"text-align:left\">".$brand."</td>
											<td style=\"text-align:left\">".$taglia."</td>
											<td style=\"text-align:left\">".$colore."</td>
											<td style=\"text-align:left\">".number_format($qta,0,",",".")."</td>
										</tr>
										";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div>

                    <div style="width:100%;float:left;text-align:center">
                        <img src="<?php  echo Mage::helper('catalog/image')->init($config, 'small_image') ?>" style="width:25%"/>
                    </div>

                    <div class="form-group" style="margin-top:30px">

                        <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php echo "window.location='prodotti-usa.php'"; ?>" />Indietro</button>

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
</html>