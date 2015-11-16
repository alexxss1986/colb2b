<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Utenti iscritti | Wisigo Product Management</title>
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
		
		include("config/percorsoMage.php");
		require_once $MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        include("config/connect.php");
        $conn = mysql_connect($HOST, $USER, $PASSWORD) or die("Connessione fallita");
        mysql_select_db($DB, $conn) or die("Impossibile selezionare il DB");
		
		$collection = Mage::getModel('customer/customer')
                            		->getCollection()
                            		->addAttributeToSelect('*')
                            		->setOrder('entity_id', "desc");

        $livello=$_SESSION['livello'];
		

		

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
				<h1 class="page-heading">UTENTI ISCRITTI<!--<small>Sub heading here</small>--></h1>
                
                <div class="the-box">
						<div class="table-responsive">


                            <?php if ($livello==0 || $livello==4) { ?>
                                <form name="form_utente" method="post" action="" >
						<table class="table table-striped table-hover table-th-block table-success" id="datatable-user">
							<thead class="the-box dark full">
								<tr>
									<th style="text-align:left;padding-left:8px">ID</th>
									<th style="text-align:left;padding-left:8px">Nome</th>
									<th style="text-align:left;padding-left:8px">Gruppo</th>
                                    <th style="text-align:left;padding-left:8px">Cliente VIP</th>
									<th style="text-align:left;padding-left:8px"></th>
								</tr>
							</thead>
							<tbody>
                            	<?php
                                $k=0;
                            	foreach ($collection as $customer) {
									$customer_id=$customer->getId();
									$customer = Mage::getModel('customer/customer')->load($customer_id);
									$nome = $customer->getFirstname();
									$cognome = $customer->getLastname();
									$gruppo=$customer->getGroupId();
									
									if ($gruppo==1){
										$descGruppo="E-Commerce";
									}
									else if ($gruppo==4){
										$descGruppo="Ebay";
									}
									
									echo "
									<tr>
										<td style=\"text-align:left\">".$customer_id."</td>
										<td style=\"text-align:left;text-transform:capitalize\">".$nome." ".$cognome."</td>
										<td style=\"text-align:left\">".$descGruppo."</td>
										<td style=\"text-align:left\"><input id=\"vip" . $k . "\" type='checkbox' name='vip' value='".$customer_id."'";

                                        $query=mysql_query("select * from cocliente_vip where id_cliente='".$customer_id."'");
                                        if (mysql_num_rows($query)==1){
                                            echo " checked disabled ";
                                        }
                                        else {
                                            echo "onclick=\"if (confirm('Sei sicuro di voler abilitare questo utente all area VIP?Verrà inviata una email al cliente per completare la registrazione vip')){document.forms['form_utente'].action='config/cliente.php?id=$customer_id';document.forms['form_utente'].submit(); } else { document.getElementById('vip".$k."').checked=false; }\"";
                                        }

                                        echo "/></td>
										<td style=\"text-align:left\"><a href='dettaglio-utente.php?id=$customer_id&dashboard=0'>Apri</a></td>
									</tr>
									";
                                    $k=$k+1;
								}
								?>
								
							</tbody>

						</table>
                            </form>
            <?php } else { ?>
                                <table class="table table-striped table-hover table-th-block table-success" id="datatable-user">
                                    <thead class="the-box dark full">
                                    <tr>
                                        <th style="text-align:left;padding-left:8px">ID</th>
                                        <th style="text-align:left;padding-left:8px">Nome</th>
                                        <th style="text-align:left;padding-left:8px">Gruppo</th>
                                        <th style="text-align:left;padding-left:8px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $k=0;
                                    foreach ($collection as $customer) {
                                        $customer_id=$customer->getId();
                                        $customer = Mage::getModel('customer/customer')->load($customer_id);
                                        $nome = $customer->getFirstname();
                                        $cognome = $customer->getLastname();
                                        $gruppo=$customer->getGroupId();

                                        if ($gruppo==1){
                                            $descGruppo="E-Commerce";
                                        }
                                        else if ($gruppo==4){
                                            $descGruppo="Ebay";
                                        }

                                        echo "
									<tr>
										<td style=\"text-align:left\">".$customer_id."</td>
										<td style=\"text-align:left;text-transform:capitalize\">".$nome." ".$cognome."</td>
										<td style=\"text-align:left\">".$descGruppo."</td>

										<td style=\"text-align:left\"><a href='dettaglio-utente.php?id=$customer_id&dashboard=0'>Apri</a></td>
									</tr>
									";
                                        $k=$k+1;
                                    }
                                    ?>

                                    </tbody>

                                </table>
            <?php } ?>
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


		
		<!-- MAIN APPS JS -->
		<script src="assets/js/apps.js"></script>
        <script src="assets/js/tabella.js"></script>
</html>