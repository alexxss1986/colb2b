<?php 
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nota Vendita | Wisigo Product Management</title>
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
	if ($_SESSION['livello']!=2) {
		
		include("config/percorsoMage.php");
		require_once $MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		
		include("config/connect.php");
$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
mysql_query("SET NAMES 'utf8' ");
		
		$id=$_REQUEST['id'];
		
		$ordine=Mage::getModel('sales/order')->loadByIncrementId($id);

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
				<h1 class="page-heading">NOTA VENDITA<!--<small>Sub heading here</small>--></h1>
                

							  
                            
                            <div class="the-box full no-border">
						<div class="table-responsive">
							<table class="table table-th-block table-success">
								<thead>
									<tr><th>Sku</th><th>Nome</th><th>Tipo</th><th>Prezzo Pagato</th><th>Prezzo Imponibile</th><th>% 3C</th><th>Da Fatturare a 3C</th></tr>
								</thead>
								<tbody>
                                	<?php 
									$_items = $ordine->getAllVisibleItems();
									foreach ($_items as $item) 
									{

                                        $countryCode = $ordine->getShippingAddress()->getCountryId();
                                        if ($countryCode == "IT") {
                                            $flagStato = "IT";
                                        } else {

                                            $stringQuery = "select count(*) from " . $resource->getTableName('country_ue') . " where stato='" . $countryCode . "'";
                                            $query = $readConnection->fetchOne($stringQuery);
                                            if ($query == 1) {
                                                $flagStato = "UE";
                                            } else {
                                                $flagStato = "E";
                                            }
                                        }


                                        // controllo se l'ordine è ivato o meno
                                        if ($flagStato == "IT") {
                                            $tipo = "VENITALIA";
                                        } else if ($flagStato == "E") {
                                            $tipo = "VENESTERO";
                                        } else if ($flagStato == "UE") {
                                            $tipo = "VENINTRA";
                                        }


                                        if ($id=="200000144" && $item->getSku()=="151450DCW000008-X0801-44"){
                                            $qtyToBeShipped = $item->getQtyOrdered() - $item->getQtyRefunded();


                                            if ($qtyToBeShipped!=0) {
                                                $iva = 22;
                                                $prezzoIvato = 317.23;


                                                $prezzoImponibile = $prezzoIvato ;

                                                // calcolo scontoTotale
                                                $prezzoProdottoOrdine = 317.23;
                                                $scontoTot = 40;


                                                if ($ordine->getCustomerGroupId() == 5 || $ordine->getCustomerGroupId() == 4) {
                                                    //$prezzoSenzaProvvigioni=$prezzoConSconto/1.06;
                                                    $percentuale = "3%";
                                                    $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                    $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                } else {
                                                    if ($scontoTot >= 20 && $scontoTot < 30) {
                                                        $percentuale = "11%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                    } else if ($scontoTot >= 30) {
                                                        $percentuale = "9%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;

                                                    } else {
                                                        $percentuale = "12%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                    }
                                                }


                                                $prezzoImponibile = number_format($prezzoImponibile, 2, ",", "");

                                                $sku = $item->getSku();
                                                $nome = $item->getName();

                                                echo "<tr>";
                                                echo "<td>" . $sku . "</td>";
                                                echo "<td>" . $nome . "</td>";
                                                echo "<td>" . $tipo . "</td>";
                                                echo "<td>" . number_format($prezzoIvato, 2, ",", "") . " €</td>";
                                                echo "<td>" . $prezzoImponibile . " €</td>";
                                                echo "<td>" . $percentuale . " - " . number_format($prezzoPercentuale, 2, ",", "") . " €</td>";
                                                echo "<td>" . number_format($prezzoSenzaProvvigioni, 2, ",", "") . " €</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
                                            $qtyToBeShipped = $item->getQtyOrdered() - $item->getQtyRefunded();

                                            if ($qtyToBeShipped!=0) {
                                                $iva = 22;

                                                if ($item->getTaxPercent()==0){
                                                    $prezzoIvato = (($item->getPrice() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                                                    $prezzoImponibile = $prezzoIvato;
                                                }
                                                else {
                                                    $prezzoIvato = (($item->getPriceInclTax() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                                                    $prezzoImponibile = $prezzoIvato / (($iva + 100) / 100);
                                                }


                                                // calcolo scontoTotale
                                                $prezzoProdottoOrdine = $item->getPrice();
                                                $scontoTot = number_format(($item->getDiscountAmount() * 100)/$prezzoProdottoOrdine, 0);


                                                if ($ordine->getCustomerGroupId() == 5 || $ordine->getCustomerGroupId() == 4) {
                                                    //$prezzoSenzaProvvigioni=$prezzoConSconto/1.06;
                                                    $percentuale = "3%";
                                                    $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                    $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                } else {
                                                    if ($scontoTot >= 20 && $scontoTot < 30) {
                                                        $percentuale = "11%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                    } else if ($scontoTot >= 30) {
                                                        $percentuale = "9%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;

                                                    } else {
                                                        $percentuale = "12%";
                                                        $prezzoSenzaProvvigioni = $prezzoIvato - ($prezzoIvato * $percentuale) / 100;
                                                        $prezzoPercentuale = ($prezzoImponibile * $percentuale) / 100;
                                                    }
                                                }


                                                $prezzoImponibile = number_format($prezzoImponibile, 2, ",", "");

                                                if ($item->getTaxPercent()!=0){
                                                    $prezzoSenzaProvvigioni = $prezzoSenzaProvvigioni / (($iva + 100) / 100);
                                                }

                                                $sku = $item->getSku();
                                                $nome = $item->getName();

                                                echo "<tr>";
                                                echo "<td>" . $sku . "</td>";
                                                echo "<td>" . $nome . "</td>";
                                                echo "<td>" . $tipo . "</td>";
                                                echo "<td>" . number_format($prezzoIvato, 2, ",", "") . " €</td>";
                                                echo "<td>" . $prezzoImponibile . " €</td>";
                                                echo "<td>" . $percentuale . " - " . number_format($prezzoPercentuale, 2, ",", "") . " €</td>";
                                                echo "<td>" . number_format($prezzoSenzaProvvigioni, 2, ",", "") . " €</td>";
                                                echo "</tr>";
                                            }



                                        }

									}
									?>
								</tbody>
							</table>
						</div><!-- /.table-responsive -->
					</div>
                    
             <div class="form-group" style="margin-top:30px">
            	
                <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='fatture-corrispettivi.php'"; ?>" />Indietro</button>
                
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