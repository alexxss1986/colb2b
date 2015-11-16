<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gestione Spedizioni | Wisigo Product Management</title>

<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- PLUGINS CSS -->
		
		<link href="assets/plugins/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
		<link href="assets/plugins/slider/slider.min.css" rel="stylesheet">
		
		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/style-responsive.css" rel="stylesheet">
        
        <script src="js/controlloform.js"></script>
        
        <script type="text/javascript">
	function calcolaDifferenza(spedizione,spedTNT,i) {
		if (spedTNT==""){
			id="differenza"+i;
			document.getElementById(id).innerHTML="";
		}
		else {
			spedTNT=spedTNT.replace(",",".");
			if (isNaN(spedTNT)){
				alert("Inserire un numero!");
				id="differenza"+i;
				id2="spedizione_tnt"+i;
				document.getElementById(id).innerHTML="";
				document.getElementById(id2).value="";
			}
			else {
				differenza=spedTNT-spedizione;
				differenza=differenza.toFixed(2);
				differenza=differenza.replace(".",",");
				id="differenza"+i;
				document.getElementById(id).innerHTML=differenza;
			}
		}
	}
</script>
        
</head>
<body class="tooltips">
<?php 
if (isset($_SESSION['username'])){
	if ($_SESSION['livello']!=2) {
		
		include("config/percorsoMage.php");
		require_once $MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		
		include("config/connect.php");
$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
		
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
						<table class="table table-striped table-hover table-th-block table-success" id="datatable-spedizione">
							<thead class="the-box dark full">
								<tr>
                                <th style="display:none"></th>
                                <th style="text-align:left;padding-left:8px">N°</th>
                                <th style="text-align:left;padding-left:8px">Spedisci al nome</th>
                                <th style="text-align:left;padding-left:8px">Costo Spedizione</th>
                                <th style="text-align:left;padding-left:8px">Spedizione TNT</th>
                                <th style="text-align:left;padding-left:8px">Differenza</th>
                                </tr>
							</thead>
							<tbody>
                            	<?php 
									$i=0;
									foreach ($orders as $order) {
										$id=$order->getIncrementId();
										$fromDate = $order->getCreatedAtStoreDate();
										
										$dataOrdine=$fromDate->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
										$time=strtotime($dataOrdine);
										$mese=date("m",$time);
										$anno=date("Y",$time);
										$giorno=date("d",$time);
										$dateOrder = $anno."".$mese."".$giorno;
										
										$nomeSpedisci=$order->getShippingAddress()->getFirstname();
										$cognomeSpedisci=$order->getShippingAddress()->getLastname();
										
										$ebay="";
										$spedizione=$order->getShippingAmount();
										if ($order->getCustomerGroupId()==5 || $order->getCustomerGroupId()==4 || $order->getPayment()->getMethodInstance()->getTitle()=="M2E Pro Payment"){
											$ebay=" - Portali";
											$spedizione=$order->getShippingAmount()*1.22;
										}
										
										echo "<tr>";
										echo "<td style=\"display:none\">".$dateOrder."</td>";
										echo "<td style=\"text-align:left\">".$id."</td>";
										echo "<td style=\"text-align:left\">".$cognomeSpedisci." ".$nomeSpedisci."<span style=\"color:red\">".$ebay."</span></td>";
										echo "<td style=\"text-align:left\">".number_format($spedizione,"2",",","");
										
										$query=mysql_query("select * from waordini_spedizioni where id_ordine='".$id."'");
										if (mysql_num_rows($query)==1){
											$tnt=mysql_result($query,0,"tnt");
											$differenza=mysql_result($query,0,"differenza");
											echo "<td style=\"text-align:left\"><input type=\"text\" name=\"spezione_tnt\" class=\"form-control\" id=\"spedizione_tnt".$i."\" style=\"width:120px;height:30px\" value=\"".number_format($tnt,2,",","")."\" disabled /><button id=\"bottone".$i."\" disabled type=\"button\" class=\"btn btn-success active\" style=\"margin-left:10px;padding:4px 12px\">OK</button></td>";
											echo "<td style=\"text-align:left\" id=\"differenza".$i."\">".number_format($differenza,"2",",","")."</td>";
											echo "</tr>";

										}
										else {
											echo "<td style=\"text-align:left\"><input type=\"text\" name=\"spezione_tnt\" class=\"form-control\" id=\"spedizione_tnt".$i."\" style=\"width:120px;height:30px\" onkeyup=\"calcolaDifferenza(".$spedizione.",this.value,".$i.")\" /><button type=\"button\" id=\"bottone".$i."\" class=\"btn btn-success active\" style=\"margin-left:10px;padding:4px 12px\" onclick=\"salvaDifferenza(".$spedizione.",".$i.",".$id.")\">OK</button></td>";
											echo "<td style=\"text-align:left\" id=\"differenza".$i."\"></td>";
											echo "</tr>";
										}
										$i=$i+1;
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
        
        <script src="js/controlloform.js"></script>
</html>