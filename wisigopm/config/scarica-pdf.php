<?php
	require('fpdf.php');
	
	include("connect.php");
				$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
				mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
				mysql_query("SET NAMES 'utf8' ");
				


		include("percorsoMage.php");

									
		$prodotto=mysql_query("select e.entity_id,e.sku,e.type_id,s.qty,v.value,o.value from cocatalog_product_entity e,cocatalog_product_entity_varchar v,cocataloginventory_stock_item s,cocatalog_product_entity_int i,coeav_attribute_option_value o where s.product_id=e.entity_id and v.entity_id=e.entity_id and v.attribute_id='71' and v.store_id=0 and i.entity_id=e.entity_id and i.attribute_id=".$id_manufacturer." and i.value=o.option_id and o.store_id=0 and e.type_id='configurable' and e.entity_id <> ALL (select entity_id from cocatalog_product_entity_media_gallery) order by e.entity_id desc");

		
		for ($i=0; $i<mysql_num_rows($prodotto); $i++){
			$sku=mysql_result($prodotto,$i,"e.sku");
			$nome=mysql_result($prodotto,$i,"v.value");
			$brand=mysql_result($prodotto,$i,"o.value");
											
			$nome=html_entity_decode($nome, ENT_QUOTES, 'utf-8');
			$brand=html_entity_decode($brand, ENT_QUOTES, 'utf-8');
			
			$data[$i][0]=$sku;
			$data[$i][1]=$nome;
			$data[$i][2]=$brand;

								
		}
		
		

	$header = array('SKU', 'NOME', 'BRAND');
	
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(40,7,'PRODOTTI SENZA IMMAGINE',0,1);
	$pdf->Ln(7);
	$pdf->SetFont('Arial','B',10);
	for ($i=0; $i<count($header); $i++){
						if ($i==0){
							$pdf->Cell(40,6,$header[$i],1,0,'C');
						}
						else if ($i==1){
							$pdf->Cell(80,6,$header[$i],1,0,'C');
						}	
						else {
							$pdf->Cell(60,6,$header[$i],1,0,'C');
						}
	}
	$pdf->Ln();
	$pdf->SetFont('Arial','',10);
	for ($i=0; $i<count($data); $i++){
		$pdf->Cell(40,6,$data[$i][0],1,0,'C');
		$pdf->Cell(80,6,$data[$i][1],1,0,'C');
		$pdf->Cell(60,6,$data[$i][2],1,0,'C');
		$pdf->Ln();			
	}
	$pdf->Output('prodotti_senza_immagine.pdf','D');