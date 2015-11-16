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
							<table class="table table-th-block table-success">

                                <?php

                                $warehouses = Mage::getModel('wgmulti/warehouse')
                                    ->getCollection()
                                    ->addOrder('position', 'ASC');
                                $warehouseData = $warehouses->toFlatArray();

                                $i=0;
                                $magazzinoArray = array();
                                $_items = $ordine->getAllItems();
                                foreach ($_items as $item) {

                                    $serializedAdditionalData = $item->getAdditionalData();
                                    if (empty($serializedAdditionalData)) {

                                    } else {
                                        $additionalData = unserialize($serializedAdditionalData);


                                        foreach ($warehouseData as $wid => $wdata) {
                                            if (array_key_exists($wid, $additionalData)) {
                                                $qty = $additionalData[$wid];
                                                if ($qty > 0) {
                                                    $magazzinoArray[$i] = $wid;
                                                    $i = $i + 1;
                                                }
                                            }
                                        }
                                    }

                                }


                                $magazzinoArray = array_unique($magazzinoArray);
                                $magazzinoArray = array_values($magazzinoArray);

                                    if ($livello==0){
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
                                    <th width="120px">Disponibile</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php


                                $magazzinoArray = array();
                                $_items = $ordine->getAllItems();
                                $i = 0;
                                foreach ($_items as $item) {

                                    $serializedAdditionalData = $item->getAdditionalData();
                                    if (empty($serializedAdditionalData)) {

                                    } else {
                                        $id = $item->getId();
                                        $sku = $item->getSku();
                                        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                        $id_p = $product->getId();
                                        $additionalData = unserialize($serializedAdditionalData);


                                        foreach ($warehouseData as $wid => $wdata) {
                                            if (array_key_exists($wid, $additionalData)) {
                                                $qty = $additionalData[$wid];
                                                if ($qty > 0) {
                                                    $magazzinoArray[$i][0] = $wid;
                                                    $magazzinoArray[$i][1] = $qty;
                                                    $magazzinoArray[$i][2] = $id_p;
                                                    $i = $i + 1;
                                                }
                                            }
                                        }
                                    }

                                }


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
                                         * 8 = BPESC
                                         */
                                        $qtyArray = array("6" => "0", "5" => "0", "1" => "0", "4" => "0", "3" => "0", "2" => "0", "8" => "0");

                                        echo "<tr>";
                                        echo "<td>" . $nome . "</td>";
                                        echo "<td>" . $sku . "</td>";
                                        for ($i = 0; $i < count($magazzinoArray); $i++) {
                                            if ($magazzinoArray[$i][2] == $id_product) {
                                                if ($magazzinoArray[$i][1] > 0) {
                                                    $indice = $magazzinoArray[$i][0];
                                                    $qtyArray[$indice] = $magazzinoArray[$i][1];
                                                }
                                            }
                                        }


                                        foreach ($qtyArray as $qty) {
                                            echo "<td>" . number_format($qty, 0, "", "") . "</td>";
                                        }


                                        $flagSINO=true;

                                        $query = mysql_query("select * from coordini_dispo where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product."'");
                                        for ($j=0; $j<mysql_num_rows($query); $j++) {
                                            $valore = mysql_result($query, 0, "disponibilita");
                                            if ($valore=="NO") {
                                                $flagSINO=false;
                                                break;
                                            }
                                        }


                                        if (mysql_num_rows($query)==0){
                                            $flagSINO2=true;
                                            $query = mysql_query("select * from coordini_arrivato where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product."'");
                                            for ($j=0; $j<mysql_num_rows($query); $j++) {
                                                $valore = mysql_result($query, 0, "arrivato");
                                                if ($valore=="NO") {
                                                    $flagSINO2=false;
                                                    break;
                                                }
                                            }

                                            if (mysql_num_rows($query)>0) {
                                                echo "<td>
												<select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
												<option selected value=\"\">...</option>
												<option value=\"SI\"";
                                                if ($flagSINO2 == true) {
                                                    echo " selected ";
                                                }
                                                echo ">SI</option>
                                                    <option value=\"NO\"";
                                                if ($flagSINO2 == false) {
                                                    echo " selected ";
                                                }
                                                echo ">NO</option>
                                                    </select>
                                                    </td>";
                                            }
                                            else {
                                                echo "<td>
												<select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
												<option selected value=\"\">...</option>
												<option value=\"SI\">SI</option>
                                                    <option value=\"NO\">NO</option>
                                                    </select>
                                                    </td>";
                                            }
                                        }
                                        else {
                                            $valore = mysql_result($query, 0, "disponibilita");
                                            echo "<td>
												<select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
												<option value=\"\">...</option>
												<option value=\"SI\"";
                                            if ($flagSINO == true) {
                                                echo " selected ";
                                            }
                                            echo ">SI</option>
												<option value=\"NO\"";
                                            if ($flagSINO == false) {
                                                echo " selected ";
                                            }
                                            echo ">NO</option>
												</select>
												</td>";
                                        }


                                        $k = $k + 1;
                                    }
                                }
                                ?>
                                </tbody>
                                    <?php

                                    }
                                    else if (($livello==2 && count($magazzinoArray)==1) || ($livello==1)){
                                        // livello boutique o livello magazzino centrale ordine singolo
                                        // questi livelli sono uguali
                                        ?>
                                        <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Id</th>
                                            <th><?php echo $codBoutique ?></th>
                                            <th width="120px">Disponibile</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        // recupero tutti i prodotti che fanno parte dell'ordine che provengono dal magazzino che si è loggato nel sistema
                                        // salvo un array contenente i magazzini e i prodotti e un array con solo i prodotti
                                        $magazzinoProdotti = array();
                                        $idArray = array();
                                        $_items = $ordine->getAllItems();
                                        $i = 0;
                                        foreach ($_items as $item) {

                                            $serializedAdditionalData = $item->getAdditionalData();
                                            if (empty($serializedAdditionalData)) {

                                            } else {
                                                $id = $item->getId();
                                                $sku = $item->getSku();
                                                $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                                $id_p = $product->getId();
                                                $additionalData = unserialize($serializedAdditionalData);


                                                foreach ($warehouseData as $wid => $wdata) {
                                                    if (array_key_exists($wid, $additionalData)) {
                                                        $qty = $additionalData[$wid];
                                                        if ($qty > 0 && $wid==$magazzino) {
                                                            $magazzinoProdotti[$i][0] = $qty;
                                                            $magazzinoProdotti[$i][1] = $id_p;
                                                            $idArray[$i] = $id_p;
                                                            $i = $i + 1;
                                                        }
                                                    }
                                                }
                                            }

                                        }



                                        // per tutti gli elementi dell'ordine, verifico se è presente nell'elenco degli articoli recuperati precedentemente
                                            $_items = $ordine->getAllVisibleItems();
                                            $k = 1;
                                            foreach ($_items as $item) {
                                                $id_p = $item->getId();
                                                $sku = $item->getSku();
                                                $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                                $esiste = false;
                                                for ($p = 0; $p < count($idArray); $p++) {
                                                    if ($idArray[$p] == $product->getId()) {
                                                        $esiste = true;
                                                    }
                                                }

                                                if ($esiste == true) {
                                                    // se l'articolo è presente significa che è stato ordinato nel magazzino loggato

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
                                                         * 8 = BPESC
                                                         */
                                                        // array delle quantità con solo il magazzino loggato
                                                        // all'inizio la setto a 0
                                                        $qtyArray = array($magazzino => "0");

                                                        echo "<tr>";
                                                        echo "<td>" . $nome . "</td>";
                                                        echo "<td>" . $sku . "</td>";
                                                        // per tutti i magazzini del prodotto, verifico se l'id del prodotto relativo è uguale a quello salvato nell'array
                                                        // se la qta è maggiore di zero, la salvo nell'array delle quantità
                                                        for ($i = 0; $i < count($magazzinoProdotti); $i++) {
                                                            if ($magazzinoProdotti[$i][1] == $id_product) {
                                                                if ($magazzinoProdotti[$i][0] > 0) {
                                                                    $qtyArray[$magazzino] = $magazzinoProdotti[$i][0];
                                                                }
                                                            }
                                                        }

                                                        // visualizzo la qta
                                                        foreach ($qtyArray as $qty) {
                                                            echo "<td>" . number_format($qty, 0, "", "") . "</td>";
                                                        }

                                                        // controllo se ho già settato la disponibilità per quell'articolo
                                                        $query = mysql_query("select * from coordini_dispo where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product . "' and id_boutique='".$magazzino."'");
                                                        if (mysql_num_rows($query) == 1) {
                                                            // disponibilità settata
                                                            $valore = mysql_result($query, 0, "disponibilita");
                                                            echo "<td>
                                                    <select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
                                                    <option value=\"\">...</option>
                                                    <option value=\"SI\"";
                                                            if ($valore == "SI") {
                                                                echo " selected ";
                                                            }
                                                            echo ">SI</option>
                                                    <option value=\"NO\"";
                                                            if ($valore == "NO") {
                                                                echo " selected ";
                                                            }
                                                            echo ">NO</option>
                                                    </select>
                                                    </td>";
                                                        } else {
                                                            // disponibilità non settata
                                                            echo "<td><select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" onchange=\"if (confirm('Confermi la tua risposta? L\\'operazione è irreversibile')) {attivaSelect('" . $id_product . "','" . $id_ordine . "','disponibile" . $k . "','".$magazzino."') } else { document.getElementsByClassName('disponibile')[0].value='';}\"><option value=\"\">...</option><option value=\"SI\">SI</option><option value=\"NO\">NO</option></select></td>";
                                                            echo "</tr>";
                                                        }

                                                        $k = $k + 1;
                                                    }
                                                }
                                            }



                                            ?>
                                        </tbody>

                                    <?php
                                    }
                                    else if ($livello==2 && count($magazzinoArray)>1) {
                                        // livello boutique e più magazzini (ordine da consolidare)
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
                                            <th>Disponibile al ritiro</th>
                                            <th width="120px">Pronto per la spedizione</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        // creo un array contente per ogni record i prodotti con il relativo magazzino dove è stato ordinato
                                        // uno stesso prodotto può essere presente in più record se è stato ordinato da diversi magazzini
                                        $magazzinoProdotti = array();
                                        $_items = $ordine->getAllItems();
                                        $i = 0;
                                        foreach ($_items as $item) {

                                            $serializedAdditionalData = $item->getAdditionalData();
                                            if (empty($serializedAdditionalData)) {

                                            } else {
                                                $id = $item->getId();
                                                $sku = $item->getSku();
                                                $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                                $id_p = $product->getId();
                                                $additionalData = unserialize($serializedAdditionalData);


                                                foreach ($warehouseData as $wid => $wdata) {
                                                    if (array_key_exists($wid, $additionalData)) {
                                                        $qty = $additionalData[$wid];
                                                        if ($qty > 0) {
                                                            $magazzinoProdotti[$i][0] = $wid;
                                                            $magazzinoProdotti[$i][1] = $qty;
                                                            $magazzinoProdotti[$i][2] = $id_p;
                                                            $i = $i + 1;
                                                        }
                                                    }
                                                }
                                            }

                                        }

                                        $k = 1;
                                        // per ogni elemento dell'array, visualizzo la riga del prodotto nell'ordine
                                        for ($i=0; $i<count($magazzinoProdotti); $i++) {
                                            $_items = $ordine->getAllVisibleItems();

                                                $id_p = $magazzinoProdotti[$i][2];
                                                $product = Mage::getModel('catalog/product')->load($id_p);


                                                if ($product->getTypeId() == "simple") {
                                                    $sku = $product->getSku();
                                                    $nome = $product->getName();
                                                    $id_product = $product->getId();

                                                    /*
                                                     * 1 = Jesi
                                                     * 2 = San benedetto
                                                     * 3 = Macerata
                                                     * 4 = Ancona
                                                     * 5 = BMDB
                                                     * 6 = PRINC
                                                     * 8 = BPESC
                                                     */
                                                    // array qta con tutti i magazzin
                                                    $qtyArray = array("6" => "0", "5" => "0", "1" => "0", "4" => "0", "3" => "0", "2" => "0", "8" => "0");

                                                    echo "<tr>";
                                                    echo "<td>" . $nome . "</td>";
                                                    echo "<td>" . $sku . "</td>";

                                                    $indice = $magazzinoProdotti[$i][0];
                                                    $qtyArray[$indice] = $magazzinoProdotti[$i][1];

                                                    foreach ($qtyArray as $qty) {
                                                        echo "<td>" . number_format($qty, 0, "", "") . "</td>";
                                                    }

                                                    if ($magazzino!=$magazzinoProdotti[$i][0]) {
                                                        // se il magazzino loggato è diverso da quello dell'array setto la disponibilità
                                                        // questo serve per il magazzino centrale che oltre a settare gli arrivi, deve settare le disponibilità per i "suoi" prodotti
                                                        // in questo caso visualizzo una label che mostra se la boutique corrispondente alla riga prodotto ha settato la disponibilità al prodottp

                                                        $queryPronto = mysql_query("select * from coordini_dispo where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product . "' and id_boutique='" . $magazzinoProdotti[$i][0] . "'");
                                                        if (mysql_num_rows($queryPronto) == 1) {
                                                            $valorePronto = mysql_result($queryPronto, 0, "disponibilita");
                                                            if ($valorePronto == "SI") {
                                                                echo "<td><div class='pronto'>&nbsp</div></td>";
                                                            } else if ($valorePronto == "NO") {
                                                                echo "<td><div class='non_pronto'>&nbsp</div></td>";
                                                            }

                                                        } else {
                                                            $valorePronto = "";
                                                            echo "<td><div class='sconosciuto'>&nbsp</div></td>";
                                                        }

                                                        // mostro la select per settare se il prodotto è arrivato
                                                        $query = mysql_query("select * from coordini_arrivato where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product . "' and id_boutique='" . $magazzinoProdotti[$i][0] . "'");
                                                        if (mysql_num_rows($query) == 1) {
                                                            $valore = mysql_result($query, 0, "arrivato");
                                                            echo "<td>
                                                        <select id=\"arrivato" . $k . "\" name=\"arrivato\" class=\"form-control arrivato\" disabled >
                                                        <option value=\"\">...</option>
                                                        <option value=\"SI\"";
                                                            if ($valore == "SI") {
                                                                echo " selected ";
                                                            }
                                                            echo ">SI</option>
                                                        <option value=\"NO\"";
                                                            if ($valore == "NO") {
                                                                echo " selected ";
                                                            }
                                                            echo ">NO</option>
                                                        </select>
                                                        </td>";
                                                        } else {
                                                            if ($valorePronto == "NO" || $valorePronto == "") {
                                                                echo "<td><select disabled id=\"arrivato" . $k . "\" name=\"arrivato\" class=\"form-control arrivato\" onchange=\"if (confirm('Confermi la tua risposta? L\\'operazione è irreversibile')) {attivaSelectArrivato('" . $id_product . "','" . $id_ordine . "','arrivato" . $k . "','".$magazzinoProdotti[$i][0]."')} else { document.getElementsByClassName('arrivato')[0].value='';}\"><option value=\"\">...</option><option value=\"SI\">SI</option><option value=\"NO\">NO</option></select></td>";
                                                            } else {
                                                                echo "<td><select id=\"arrivato" . $k . "\" name=\"arrivato\" class=\"form-control arrivato\" onchange=\"if (confirm('Confermi la tua risposta? L\\'operazione è irreversibile')) {attivaSelectArrivato('" . $id_product . "','" . $id_ordine . "','arrivato" . $k . "','".$magazzinoProdotti[$i][0]."')} else { document.getElementsByClassName('arrivato')[0].value='';}\"><option value=\"\">...</option><option value=\"SI\">SI</option><option value=\"NO\">NO</option></select></td>";
                                                            }
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    else {
                                                        // in questo caso il magazzino loggato è il magazzino principale quindi non faccio vedere la label e mostro solo la select per gli arrivi
                                                        // in questo caso la select per gli arrivi è una sort di controllo disponibilità
                                                        echo "<td></td>";

                                                        $query = mysql_query("select * from coordini_arrivato where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product . "' and id_boutique='" . $magazzinoProdotti[$i][0] . "'");
                                                        if (mysql_num_rows($query) == 1) {
                                                            $valore = mysql_result($query, 0, "arrivato");
                                                            echo "<td>
                                                        <select id=\"arrivato" . $k . "\" name=\"arrivato\" class=\"form-control arrivato\" disabled >
                                                        <option value=\"\">...</option>
                                                        <option value=\"SI\"";
                                                            if ($valore == "SI") {
                                                                echo " selected ";
                                                            }
                                                            echo ">SI</option>
                                                        <option value=\"NO\"";
                                                            if ($valore == "NO") {
                                                                echo " selected ";
                                                            }
                                                            echo ">NO</option>
                                                        </select>
                                                        </td>";
                                                        } else {
                                                            echo "<td><select id=\"arrivato" . $k . "\" name=\"arrivato\" class=\"form-control arrivato\" onchange=\"if (confirm('Confermi la tua risposta? L\\'operazione è irreversibile')) {attivaSelectArrivato('" . $id_product . "','" . $id_ordine . "','arrivato" . $k . "','".$magazzinoProdotti[$i][0]."')} else { document.getElementsByClassName('arrivato')[0].value='';}\"><option value=\"\">...</option><option value=\"SI\">SI</option><option value=\"NO\">NO</option></select></td>";

                                                            echo "</tr>";
                                                        }
                                                    }




                                                    $k = $k + 1;
                                                }
                                        }
                                        ?>
                                        </tbody>

                                        <?php
                                    }
                                else if ($livello==3){
                                    ?>
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Id</th>
                                        <th width="120px">Disponibile</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php


                                    $magazzinoArray = array();
                                    $_items = $ordine->getAllItems();
                                    $i = 0;
                                    foreach ($_items as $item) {

                                        $serializedAdditionalData = $item->getAdditionalData();
                                        if (empty($serializedAdditionalData)) {

                                        } else {
                                            $id = $item->getId();
                                            $sku = $item->getSku();
                                            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                            $id_p = $product->getId();
                                            $additionalData = unserialize($serializedAdditionalData);


                                            foreach ($warehouseData as $wid => $wdata) {
                                                if (array_key_exists($wid, $additionalData)) {
                                                    $qty = $additionalData[$wid];
                                                    if ($qty > 0) {
                                                        $magazzinoArray[$i][0] = $wid;
                                                        $magazzinoArray[$i][1] = $qty;
                                                        $magazzinoArray[$i][2] = $id_p;
                                                        $i = $i + 1;
                                                    }
                                                }
                                            }
                                        }

                                    }


                                    $_items = $ordine->getAllVisibleItems();
                                    $k = 1;
                                    foreach ($_items as $item) {
                                        $id_p = $item->getId();
                                        $sku = $item->getSku();
                                        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

                                        if ($product->getTypeId() == "simple") {
                                            $nome = $item->getName();
                                            $id_product = $product->getId();


                                            echo "<tr>";
                                            echo "<td>" . $nome . "</td>";
                                            echo "<td>" . $sku . "</td>";


                                            $flagSINO=true;
                                            $query = mysql_query("select * from coordini_dispo where id_ordine='" . $id_ordine . "' and id_prodotto='" . $id_product."'");
                                            for ($j=0; $j<mysql_num_rows($query); $j++) {
                                                $valore = mysql_result($query, 0, "disponibilita");
                                                if ($valore=="NO") {
                                                    $flagSINO=false;
                                                    break;
                                                }
                                            }


                                            if (mysql_num_rows($query)==0){
                                                echo "<td>
												<select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
												<option selected value=\"\">...</option>
												<option value=\"SI\">SI</option>
												<option value=\"NO\">NO</option>
												</select>
												</td>";
                                            }
                                            else {
                                                $valore = mysql_result($query, 0, "disponibilita");
                                                echo "<td>
												<select id=\"disponibile" . $k . "\" name=\"disponibile\" class=\"form-control disponibile\" disabled >
												<option value=\"\">...</option>
												<option value=\"SI\"";
                                                if ($flagSINO == true) {
                                                    echo " selected ";
                                                }
                                                echo ">SI</option>
												<option value=\"NO\"";
                                                if ($flagSINO == false) {
                                                    echo " selected ";
                                                }
                                                echo ">NO</option>
												</select>
												</td>";
                                            }


                                            $k = $k + 1;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                <?php

                                }
                                ?>
                            </table>
                            <?php
                            if ($livello==0 || ($livello==2 && count($magazzinoArray)>1) || $livello==3){
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
                            }
                            else {
                                // se il livello è boutique, visualizzo le foto dei prodotti dell'ordine corrispondenti a quella boutique
                                echo '<div style="width:100%;float:left;margin-bottom:20px">';
                                foreach ($_items as $item) {
                                    $id_p = $item->getId();
                                    $sku = $item->getSku();
                                    $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
                                    $esiste = false;
                                    for ($p = 0; $p < count($idArray); $p++) {
                                        if ($idArray[$p] == $product->getId()) {
                                            $esiste = true;
                                        }
                                    }

                                    if ($esiste == true) {
                                        $idProdottoConfigurabile = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                                        $prodottoConfigurabile = Mage::getModel('catalog/product')->load($idProdottoConfigurabile[0]);
                                        $immagine = Mage::helper('catalog/image')->init($prodottoConfigurabile, 'small_image');

                                        echo '<img src="' . $immagine . '" style="width:25%"/>';
                                    }

                                }
                                echo "</div>";
                            }
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