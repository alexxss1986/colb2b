<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettaglio Utente | Wisigo Product Management</title>
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
<?php  if (isset($_SESSION['username'])){
	if ($_SESSION['livello']!=2) {
		
		include("config/percorsoMage.php");
		require_once $MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		
		$id=$_REQUEST['id'];
		$dashboard=$_REQUEST['dashboard'];
		
		$customer = Mage::getModel('customer/customer')->load($id);
		$nome = $customer->getFirstname();
		$cognome = $customer->getLastname();
		$gruppo=$customer->getGroupId();
		$email=$customer->getEmail();
		$sesso=$customer->getResource()->getAttribute("gender")->getSource()->getOptionText($customer->getData('gender'));
		$data=$customer->getDob();
		$toDateFormat = 'd/m/Y';
		$data = Mage::getModel('core/date')->date($toDateFormat , strtotime($data));
		
		if ($gruppo==1){
			$descGruppo="E-Commerce";
		}
		else if ($gruppo==4){
			$descGruppo="Ebay";
		}		
		
		if ($sesso=="Male"){
			$sesso="Maschio";
		}
		else if ($sesso=="Female"){
			$sesso="Femmina";
		}


		

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
				<h1 class="page-heading">DETTAGLI CLIENTE<!--<small>Sub heading here</small>--></h1>
                
                <div class="panel panel-success">
							  <div class="panel-heading">
								<h3 class="panel-title">Dettagli cliente</h3>
							  </div>
							  <div class="panel-body">                              	
								<p><b>Nome:</b>  <?php echo $nome ?></p>
                                <p><b>Cognome:</b>  <?php echo $cognome ?><p>
                                <p><b>Gruppo:</b>  <?php echo $descGruppo ?><p>
                                <p><b>Email:</b>  <?php echo $email; ?><p>
                                <p><b>Data di nascita:</b>  <?php echo $data; ?></p>
                                <p><b>Sesso:</b>  <?php echo $sesso; ?></p>
                                
							  </div><!-- /.panel-body -->
							  
							</div>
                            
               <div class="form-group" style="margin-top:30px">
            	
                 <?php 
				if ($dashboard==1){
				?>
                 <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='dashboard.php'"; ?>" />Indietro</button>
               <?php 
				}
				else if ($dashboard==0){
				?>
                <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='utenti.php'"; ?>" />Indietro</button>
                <?php 
				}
				?>
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