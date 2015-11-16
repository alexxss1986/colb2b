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
			->addFieldToFilter('status', 'complete')
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
                                <th style="text-align:left;padding-left:8px">N°</th>
                                <th style="text-align:left;padding-left:8px">Acquistato il</th>
                                <th style="text-align:left;padding-left:8px">Fattura per nome</th>
                                <th style="text-align:left;padding-left:8px">Spedisci al nome</th>
                                <th style="text-align:left;padding-left:8px">Stato</th>
                                <th style="text-align:left;padding-left:8px"></th>
                                </tr>
							</thead>
							<tbody>
                            	<?php 
									foreach ($orders as $order) {
										$id=$order->getIncrementId();
										$fromDate = $order->getCreatedAtStoreDate();
										
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
										echo "<td style=\"text-align:left\">".$id."</td>";
										echo "<td style=\"text-align:left\">".$fromDate."</td>";
										echo "<td style=\"text-align:left;\">".$cognomeFattura." ".$nomeFattura."</td>";
										echo "<td style=\"text-align:left\">".$cognomeSpedisci." ".$nomeSpedisci."</td>";
										echo "<td style=\"text-align:left\"><span class=\"label label-success\">Completo</span>";
										echo "<td style=\"text-align:left\"><a href=\"dettaglio-ordine.php?id=$id\">Dettaglio ordine</a></td>";
										echo "</tr>";
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