<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Catalogo prodotti | Wisigo Product Management</title>
<script type="text/javascript" src="js/controlloform.js"></script>

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
	if ($_SESSION['livello']!=1) {
		
		include("config/connect.php");
				$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
				mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
				mysql_query("SET NAMES 'utf8' ");
				
		include("config/percorsoMage.php");
		require_once $MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect(array('name', 'entity_id','sku','ca_codice_produttore','ca_brand'))
            ->addAttributeToFilter("type_id","configurable");



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
				<h1 class="page-heading">CATALOGO PRODOTTI<!--<small>Sub heading here</small>--></h1>
                
                <div class="the-box">
						<div class="table-responsive">
						<table class="table table-striped table-hover table-th-block table-success" id="datatable-product">
							<thead class="the-box dark full">
								<tr>
                                	<th style="display:none">ID</th>
									<th style="text-align:left;padding-left:8px">SKU</th>
                                    <th style="text-align:left;padding-left:8px">Nome</th>
                                    <th style="text-align:left;padding-left:8px">Brand</th>
                                    <th style="text-align:left;padding-left:8px">Codice fornitore</th>
                                    <th style="text-align:left;padding-left:8px" class="prova"></th>
								</tr>
							</thead>
							<tbody>
                            	<?php

                                foreach ($collection as $prodotto) {
                                    $id=$prodotto->getId();
                                    $sku=$prodotto->getSku();
                                    $nome=$prodotto->getName();
									$brand=$prodotto->getAttributeText("ca_brand");
                                    $codice_fornitore=$prodotto->getData("ca_codice_produttore");

										echo "
									<tr>									
										<td style=\"display:none\">".$id."</td>
										<td style=\"text-align:left\">".$sku."</td>
										<td style=\"text-align:left\">".$nome."</td>
										<td style=\"text-align:left\">".$brand."</td>
										<td style=\"text-align:left\">".$codice_fornitore."</td>
										<td style=\"text-align:left\"><a href='dettaglio-prodotto.php?id=$id'>Apri</a></td>
									</tr>
									";
									

								
								}
                            	
								?>
								
							</tbody>

						</table>
						</div><!-- /.table-responsive -->
					</div><!-- /.the-box .default -->
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