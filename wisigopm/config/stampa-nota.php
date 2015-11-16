<?php
	
	require('mc_table.php');
	
	include("connect.php");
				$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
				mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
				mysql_query("SET NAMES 'utf8' ");
				

		include("percorsoMage.php");
		require_once "../".$MAGE;
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		
		$check=$_REQUEST['check'];
		$k=0;
		$totalePrezzoIvato=0;
		$totalePrezzoPercentuale=0;
		$totalePrezzoSenzaProvvigioni=0;
		$totalePrezzoImponibile=0;
		
		for ($i=0; $i<count($check); $i++) {
			$ordine=Mage::getModel('sales/order')->loadByIncrementId($check[$i]);
			$_items = $ordine->getItemsCollection();
			foreach ($_items as $item) 
			{
				
				$iva=22;
				$prezzoIvato[$k]=$item->getPriceInclTax()-($item->getPriceInclTax()*$item->getDiscountPercent())/100;
				$prezzoImponibile=$prezzoIvato[$k]/(($iva+100)/100);
				
				$totalePrezzoImponibile=$totalePrezzoImponibile+$prezzoImponibile;
				
				$prezzoImponibile2[$k]=number_format($prezzoImponibile,2,",","");

                // calcolo scontoTotale
                $productid = Mage::getModel('catalog/product')->getIdBySku(trim($item->getSku()));
                $_product = Mage::getModel('catalog/product');
                $_product ->load($productid);

                $prezzoProdotto=$_product->getPrice();
                $prezzoProdottoOrdine=$item->getPrice();

                $getpercentage = number_format($prezzoProdottoOrdine / $prezzoProdotto * 100, 2);
                $scontoCatalogo = 100 - $getpercentage;
                $scontoCoupon = $item->getDiscountPercent();


                $scontoTot=$scontoCatalogo+$scontoCoupon;

				if ($ordine->getCustomerGroupId()==5 || $ordine->getCustomerGroupId()==4){
					$prezzoSenzaProvvigioni=$prezzoIvato[$k]-($prezzoIvato[$k]*6)/100;
					$percentuale[$k]="3%";
					$prezzoPercentuale[$k]=($prezzoImponibile2[$k]*6)/100;

				}
				else {
                    if ($scontoTot >= 20 && $scontoTot < 30) {
						$prezzoSenzaProvvigioni=$prezzoIvato[$k]-($prezzoIvato[$k]*16)/100;
						$percentuale[$k]="11%";
						$prezzoPercentuale[$k]=($prezzoImponibile2[$k]*16)/100;
					}
                    else if ($scontoTot >= 30) {
                        $prezzoSenzaProvvigioni=$prezzoIvato[$k]-($prezzoIvato[$k]*16)/100;
                        $percentuale[$k]="9%";
                        $prezzoPercentuale[$k]=($prezzoImponibile2[$k]*16)/100;
                    }
					else {
						$prezzoSenzaProvvigioni=$prezzoIvato[$k]-($prezzoIvato[$k]*10)/100;
						$percentuale[$k]="12%";
						$prezzoPercentuale[$k]=($prezzoImponibile2[$k]*10)/100;
					}
				}
				

										
				$prezzoSenzaProvvigioni2[$k]=$prezzoSenzaProvvigioni/(($iva+100)/100);
					
				$sku[$k]=$item->getSku();
				$nome[$k]=$item->getName();
				$id_ordine[$k]=$check[$i];
				$totalePrezzoIvato=$totalePrezzoIvato+$prezzoIvato[$k];
				$totalePrezzoPercentuale=$totalePrezzoPercentuale+$prezzoPercentuale[$k];
				$totalePrezzoSenzaProvvigioni=$totalePrezzoSenzaProvvigioni+$prezzoSenzaProvvigioni2[$k];

				$k=$k+1;

			}
		
		}
		
		
	define('EURO', chr(128));
	
	$pdf=new PDF_MC_Table();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(40,7,'NOTA VENDITA',0,1);
	$pdf->Ln(7);
	$pdf->SetWidths(array(22,40,30,40,30,30));
	srand(microtime()*1000000);
	$pdf->SetFont('Arial','B',10);	
	$pdf->Row(array('ID ORDINE','PRODOTTO', 'PREZZO IVATO', 'PREZZO IMPONIBILE','% 3C' ,'DA FATT. A 3C'));
	$pdf->SetFont('Arial','',10);
	for ($i=0; $i<count($sku); $i++){
		$prodotto=$sku[$i]."\n".$nome[$i];
		$pdf->Row(array($id_ordine[$i],$prodotto,number_format($prezzoIvato[$i],2,",","")." ".EURO,$prezzoImponibile2[$i]." ".EURO,$percentuale[$i]." - ".number_format($prezzoPercentuale[$i],2,",","")." ".EURO,number_format($prezzoSenzaProvvigioni2[$i],2,",","")." ".EURO));
	}
	
	$pdf->SetFont('Arial','B',10);	
	$pdf->Row(array("TOTALE","",number_format($totalePrezzoIvato,2,",","")." ".EURO,number_format($totalePrezzoImponibile,2,",","")." ".EURO,number_format($totalePrezzoPercentuale,2,",","")." ".EURO,number_format($totalePrezzoSenzaProvvigioni,2,",","")." ".EURO));
	$pdf->Output('nota_vendita.pdf','D');
	
	