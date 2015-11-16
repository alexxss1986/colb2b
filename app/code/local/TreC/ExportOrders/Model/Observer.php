<?php
class TreC_ExportOrders_Model_Observer
{
	public function export($observer){

        $path="/home/coltortiboutique/public_html/var/ordini/";
        if (!is_dir($path)) {

            mkdir($path);
        }
		
		
		$countCOR=0;
		$countODA=0;
		$countODV=0;
		$i=0;
		$arrayODA=array("");
		$arrayCOR=array("");
		$arrayODV=array("");
		$flagStato=0;
        $sommaProdotti=0;


	
		$order = $observer->getOrder();
		if($order->getState() == Mage_Sales_Model_Order::STATE_COMPLETE ){

            $codiceCommessa = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/codice_commessa');
            $codiceFornitore = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/codice_fornitore');
            $codiceCliente = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/codice_cliente');
            $limiteInferiore = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/limite_inferiore');
            $limiteSuperiore = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/limite_superiore');
            $percentualePrezzoPieno = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/percentuale_prezzo_pieno');
            $percentualeSconto1 = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/percentuale_sconto1');
            $percentualeSconto2 = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/percentuale_sconto2');
            $percentualeMarketplace = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/percentuale_marketplace');
            $speseCartaEur = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/banca_carta_eur');
            $speseCartaNoEur = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/banca_carta_noeur');
            $tariffaCarta = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/spese_carta');
            $spesePaypal = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/banca_paypal');
            $tariffaPaypal = Mage::getStoreConfig('config_cliente_sezione/gruppo_cliente/spese_paypal');

            if (isset($codiceCommessa) && $codiceCommessa!="" && isset($codiceCliente) && $codiceCliente!="" && isset($codiceFornitore) && $codiceFornitore!="" && isset($limiteInferiore) && $limiteInferiore!="" && isset($limiteSuperiore) && $limiteSuperiore!="" && isset($percentualePrezzoPieno) && $percentualePrezzoPieno!="" && isset($percentualeSconto1) && $percentualeSconto1!="" && isset($percentualeSconto2) && $percentualeSconto2!="" && isset($percentualeMarketplace) && $percentualeMarketplace!="" && isset($speseCartaEur) && $speseCartaEur!="" && isset($speseCartaNoEur) && $speseCartaNoEur!="" && isset($tariffaCarta) && $tariffaCarta!="" && isset($spesePaypal) && $spesePaypal!="" && isset($tariffaPaypal) && $tariffaPaypal!="") {

                $resource = Mage::getSingleton('core/resource');

                $readConnection = $resource->getConnection('core_read');


                $COR = 'COR_'.$codiceFornitore.'_' . $order->getIncrementId() . '.txt';
                $ODA = 'ODA_'.$codiceFornitore.'_' . $order->getIncrementId() . '.txt';
                $ODV = 'ODV_'.$codiceFornitore.'_' . $order->getIncrementId() . '.txt';

                // recupero data dell'ordine nel formato yyyy-mm-dd HH:mm
                $dateOrder = $order->getCreatedAtStoreDate()->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

                // data di consegna della merce impostata a 4 giorni in più della data dell'ordine. Formato ddmmyyyy
                $dateDelivery = date('dmY', strtotime($dateOrder . " +4 days"));

                // modifica formato della data dell'ordine in ddmmyyyy
                $time = strtotime($dateOrder);
                $mese = date("m", $time);
                $anno = date("Y", $time);
                $giorno = date("d", $time);
                $dateOrder = $giorno . "" . $mese . "" . $anno;

                $giorni = date("t", strtotime($anno . "-" . $mese . "-" . $giorno));   // calcolo numero giorni di un mese


                $dataInizioMese = "01" . $mese . "" . $anno;   // data inizio del mese corrente dell'ordine
                $dataFineMese = $giorni . $mese . "" . $anno;  // data fine del mese corrente dell'ordine

                // controllo per stampare il mese senza lo "0" iniziale se presente
                if (substr($mese, 0, 1) == 0) {
                    $mese = substr($mese, 1, 1);
                }

                // prima riga file ODA
                $arrayODA[$countODA][0] = "TES";
                $arrayODA[$countODA][1] = "701";
                $arrayODA[$countODA][2] = $dateOrder;
                $arrayODA[$countODA][3] = "123";
                $arrayODA[$countODA][4] = $order->getIncrementId();
                $arrayODA[$countODA][5] = $codiceFornitore;
                $arrayODA[$countODA][6] = "EUR";
                $arrayODA[$countODA][7] = "1";
                $arrayODA[$countODA][8] = "";
                $arrayODA[$countODA][9] = "X006";


                // prima riga file COR
                $arrayCOR[$countCOR][0] = "TES";
                $arrayCOR[$countCOR][1] = "0";
                $arrayCOR[$countCOR][2] = "701";
                $arrayCOR[$countCOR][3] = $anno;
                $arrayCOR[$countCOR][4] = $dateOrder;
                $arrayCOR[$countCOR][5] = "123";
                $arrayCOR[$countCOR][6] = "";
                $arrayCOR[$countCOR][7] = $dateOrder;
                $arrayCOR[$countCOR][8] = $order->getIncrementId();
                $arrayCOR[$countCOR][9] = "1";
                $arrayCOR[$countCOR][10] = "eCommerce Rif Ord N. " . $order->getIncrementId();
                $arrayCOR[$countCOR][11] = $anno;
                $arrayCOR[$countCOR][12] = $mese;
                $arrayCOR[$countCOR][13] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");

                $_items = $order->getItemsCollection();
                foreach ($_items as $item) {
                    if ($item->getParentItem()) continue;

                    $qtyToBeShipped = $item->getQtyOrdered() - $item->getQtyRefunded();

                    if ($qtyToBeShipped!=0) {

                        // calcolo scontoTotale
                        $prezzoProdottoOrdine = $item->getPrice();
                        $scontoTot = number_format(($item->getDiscountAmount() * 100)/$prezzoProdottoOrdine, 0);

                        if ($i == 0) {
                            $iva = $item->getTaxPercent();


                            // a seconda dello stato del cliente che ha acquistato avremo un codice conto Esolver diverso
                            // ITA  codice: 61800001
                            // paesi UE  codice: 61800002
                            // paesi Estero  codice: 61800003
                            $countryCode = $order->getShippingAddress()->getCountryId();
                            if ($countryCode == "IT") {
                                $codiceConto = "61800001";
                                $flagStato = "IT";
                            } else {

                                $stringQuery = "select count(*) from " . $resource->getTableName('country_ue') . " where stato='" . $countryCode . "'";
                                $query = $readConnection->fetchOne($stringQuery);
                                if ($query == 1) {
                                    $codiceConto = "61800002";
                                    $flagStato = "UE";
                                    $iva = 22;
                                } else {
                                    $codiceConto = "61800003";
                                    $flagStato = "E";
                                    $iva = 22;
                                }
                            }


                            $arrayODA[$countODA][10] = "2";

                            // controllo se l'ordine è ivato o meno
                            if ($flagStato == "IT") {
                                $arrayODA[$countODA][11] = "VENITALIA";
                            } else if ($flagStato == "E") {
                                $arrayODA[$countODA][11] = "VENESTERO";
                            } else if ($flagStato == "UE") {
                                $arrayODA[$countODA][11] = "VENINTRA";
                            }

                            $arrayODA[$countODA][12] = $item->getSku();
                            $arrayODA[$countODA][13] = $dateDelivery;
                            $arrayODA[$countODA][14] = number_format($qtyToBeShipped, 0, ".", "");


                            if ($item->getTaxPercent()==0){
                                $prezzoConSconto = (($item->getPrice() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                            }
                            else {
                                $prezzoConSconto = (($item->getPriceInclTax() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                            }

                            // a seconda del gruppo di utente che ha effettuato un ordine viene calcolato un prezzo diverso (ecommerce o marketplace)
                            if ($order->getCustomerGroupId() == 5 || $order->getCustomerGroupId() == 4 || $order->getPayment()->getMethodInstance()->getTitle() == "M2E Pro Payment") {
                                //$prezzoSenzaProvvigioni=$prezzoConSconto/1.06;
                                $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeMarketplace) / 100;
                            } else {
                                if ($scontoTot >= $limiteInferiore && $scontoTot < $limiteSuperiore) {
                                    $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeSconto1) / 100;
                                } else if ($scontoTot >= $limiteSuperiore) {
                                    $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeSconto2) / 100;

                                } else {
                                    $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualePrezzoPieno) / 100;
                                }
                            }

                            // scorporo l'iva se l'ordine è ivato. (Solo per il file ODA)
                            if ($item->getTaxPercent()!=0){
                                $prezzoSenzaProvvigioni = $prezzoSenzaProvvigioni / (($iva + 100) / 100);
                            }


                            $arrayODA[$countODA][15] = number_format($prezzoSenzaProvvigioni, 2, ".", "");
                            $arrayODA[$countODA][16] = $codiceCommessa;


                            $arrayCOR[$countCOR][14] = $codiceConto;

                            $arrayCOR[$countCOR][15] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                            if ($flagStato == "IT") {
                                $arrayCOR[$countCOR][16] = number_format($iva, 0, ".", "");
                                $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2) / (($iva + 100) / 100);
                                $imposta = $imponibile * $iva / 100;
                            } else if ($flagStato == "UE") {
                                $arrayCOR[$countCOR][16] = "22UE";
                                $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2) / (($iva + 100) / 100);
                                $imposta = $imponibile * $iva / 100;
                            } else if ($flagStato == "E") {
                                $arrayCOR[$countCOR][16] = "NI08";
                                $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2);
                                $imposta = 0;
                            }


                            $arrayCOR[$countCOR][17] = number_format($imponibile, 2, ".", "");
                            $arrayCOR[$countCOR][18] = number_format($imposta, 2, ".", "");

                            $arrayCOR[$countCOR][19] = "Totale Ricavi Corrispettivi";
                            $arrayCOR[$countCOR][20] = "3C_001";  // fisso per cliente
                            $arrayCOR[$countCOR][21] = $codiceCommessa;    // fisso per cliente
                            $arrayCOR[$countCOR][22] = "010120";// fisso per cliente
                            $arrayCOR[$countCOR][23] = "010120";// fisso per cliente
                            $arrayCOR[$countCOR][24] = $dataInizioMese;
                            $arrayCOR[$countCOR][25] = $dataFineMese;

                            if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata") {
                                $arrayCOR[$countCOR][26] = "42900130";  // codice conto banca fisso
                            }
                            else if ($order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                                $arrayCOR[$countCOR][26] = "57100060";
                            }
                            else {
                                $arrayCOR[$countCOR][26] = "42900120";  // codice conto banca fisso
                            }


                            $arrayCOR[$countCOR][27] = "1";   // numero banca fisso
                            $arrayCOR[$countCOR][28] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                            $arrayCOR[$countCOR][29] = "0";
                            $arrayCOR[$countCOR][30] = "Incasso Corrispettivi";

                            $countCOR = $countCOR + 1;
                            $countODA = $countODA + 1;


                        }

                        // righe aggiuntive con i prodotti

                        $arrayODA[$countODA][0] = "RIG";
                        $arrayODA[$countODA][1] = "701";
                        $arrayODA[$countODA][2] = $dateOrder;
                        $arrayODA[$countODA][3] = "123";
                        $arrayODA[$countODA][4] = $order->getIncrementId();
                        $arrayODA[$countODA][5] = $codiceFornitore;
                        $arrayODA[$countODA][6] = "EUR";
                        $arrayODA[$countODA][7] = "1";
                        $arrayODA[$countODA][8] = "";
                        $arrayODA[$countODA][9] = "X006";
                        $arrayODA[$countODA][10] = "2";
                        // controllo se l'ordine è ivato o meno
                        if ($flagStato == "IT") {
                            $arrayODA[$countODA][11] = "VENITALIA";
                        } else if ($flagStato == "E") {
                            $arrayODA[$countODA][11] = "VENESTERO";
                        } else if ($flagStato == "UE") {
                            $arrayODA[$countODA][11] = "VENINTRA";
                        }
                        $arrayODA[$countODA][12] = $item->getSku();
                        $arrayODA[$countODA][13] = $dateDelivery;
                        $arrayODA[$countODA][14] = number_format($qtyToBeShipped, 0, ".", "");

                        if ($item->getTaxPercent()==0){
                            $prezzoConSconto = (($item->getPrice() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                        }
                        else {
                            $prezzoConSconto = (($item->getPriceInclTax() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped)) / $qtyToBeShipped;
                        }

                        // a seconda del gruppo di utente che ha effettuato un ordine viene calcolato un prezzo diverso (ecommerce o marketplace)
                        if ($order->getCustomerGroupId() == 5 || $order->getCustomerGroupId() == 4 || $order->getPayment()->getMethodInstance()->getTitle() == "M2E Pro Payment") {
                            //$prezzoSenzaProvvigioni=$prezzoConSconto/1.06;
                            $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeMarketplace) / 100;
                        } else {
                            if ($scontoTot >= $limiteInferiore && $scontoTot < $limiteSuperiore) {
                                $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeSconto1) / 100;
                            } else if ($scontoTot >= $limiteSuperiore) {
                                $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualeSconto2) / 100;

                            } else {
                                $prezzoSenzaProvvigioni = $prezzoConSconto - ($prezzoConSconto * $percentualePrezzoPieno) / 100;
                            }
                        }

                        // scorporo l'iva se l'ordine è ivato. (Solo per il file ODA)
                        if ($item->getTaxPercent()!=0){
                            $prezzoSenzaProvvigioni = $prezzoSenzaProvvigioni / (($iva + 100) / 100);
                        }

                        $arrayODA[$countODA][15] = number_format($prezzoSenzaProvvigioni, 2, ".", "");
                        $arrayODA[$countODA][16] = $codiceCommessa;


                        $arrayCOR[$countCOR][0] = "RIG";
                        $arrayCOR[$countCOR][1] = "0";
                        $arrayCOR[$countCOR][2] = "701";
                        $arrayCOR[$countCOR][3] = $anno;
                        $arrayCOR[$countCOR][4] = $dateOrder;
                        $arrayCOR[$countCOR][5] = "123";
                        $arrayCOR[$countCOR][6] = "";
                        $arrayCOR[$countCOR][7] = $dateOrder;
                        $arrayCOR[$countCOR][8] = $order->getIncrementId();
                        $arrayCOR[$countCOR][9] = "1";
                        $arrayCOR[$countCOR][10] = "eCommerce Rif Ord N. " . $order->getIncrementId();
                        $arrayCOR[$countCOR][11] = $anno;
                        $arrayCOR[$countCOR][12] = $mese;
                        $arrayCOR[$countCOR][13] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                        $arrayCOR[$countCOR][14] = $codiceConto;


                        $prezzoConSconto = (($item->getPriceInclTax() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped));
                        $arrayCOR[$countCOR][15] = number_format($prezzoConSconto, 2, ".", "");
                        if ($flagStato == "IT") {
                            $arrayCOR[$countCOR][16] = number_format($iva, 0, ".", "");
                            $imponibile = round($prezzoConSconto, 2) / (($iva + 100) / 100);
                            $imposta = $imponibile * $iva / 100;
                        } else if ($flagStato == "UE") {
                            $arrayCOR[$countCOR][16] = "22UE";
                            $imponibile = round($prezzoConSconto, 2) / (($iva + 100) / 100);
                            $imposta = $imponibile * $iva / 100;
                        } else if ($flagStato == "E") {
                            $arrayCOR[$countCOR][16] = "NI08";
                            $imponibile = round($prezzoConSconto, 2);
                            $imposta = 0;
                        }

                        $sommaProdotti=$sommaProdotti+round($prezzoConSconto,2);

                        $arrayCOR[$countCOR][17] = number_format($imponibile, 2, ".", "");
                        $arrayCOR[$countCOR][18] = number_format($imposta, 2, ".", "");

                        $arrayCOR[$countCOR][19] = "Totale Ricavi Corrispettivi";
                        $arrayCOR[$countCOR][20] = "3C_001";
                        $arrayCOR[$countCOR][21] = $codiceCommessa;
                        $arrayCOR[$countCOR][22] = "010120";
                        $arrayCOR[$countCOR][23] = "010120";
                        $arrayCOR[$countCOR][24] = $dataInizioMese;
                        $arrayCOR[$countCOR][25] = $dataFineMese;
                        if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata") {
                            $arrayCOR[$countCOR][26] = "42900130";  // codice conto banca fisso
                        }
                        else if ($order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                            $arrayCOR[$countCOR][26] = "57100060";
                        }
                        else {
                            $arrayCOR[$countCOR][26] = "42900120";  // codice conto banca fisso
                        }
                        $arrayCOR[$countCOR][27] = "1";
                        $arrayCOR[$countCOR][28] = number_format($prezzoConSconto, 2, ".", "");
                        $arrayCOR[$countCOR][29] = "0";
                        $arrayCOR[$countCOR][30] = "Incasso Corrispettivi";


                        $countCOR = $countCOR + 1;
                        $countODA = $countODA + 1;

                        $i = $i + 1;
                    }
                }

                // controllo se sono presenti spese di spedizione
                // se le spese di spedizione ci sono si inserisce una riga nel file COR
                // se le spese di sepdizione non ci sono si inserisce una riga nel file ODV
                if ($order->getShippingAmount() != 0) {
                    $arrayCOR[$countCOR][0] = "RIG";
                    $arrayCOR[$countCOR][1] = "0";
                    $arrayCOR[$countCOR][2] = "701";
                    $arrayCOR[$countCOR][3] = $anno;
                    $arrayCOR[$countCOR][4] = $dateOrder;
                    $arrayCOR[$countCOR][5] = "123";
                    $arrayCOR[$countCOR][6] = "";
                    $arrayCOR[$countCOR][7] = $dateOrder;
                    $arrayCOR[$countCOR][8] = $order->getIncrementId();
                    $arrayCOR[$countCOR][9] = "1";
                    $arrayCOR[$countCOR][10] = "eCommerce Rif Ord N. " . $order->getIncrementId();
                    $arrayCOR[$countCOR][11] = $anno;
                    $arrayCOR[$countCOR][12] = $mese;
                    $arrayCOR[$countCOR][13] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                    $arrayCOR[$countCOR][14] = "61800008";  // codice conto spese trasporto

                    if ($order->getCustomerGroupId() == 5 || $order->getCustomerGroupId() == 4 || $order->getPayment()->getMethodInstance()->getTitle() == "M2E Pro Payment") {
                        $spedizionePortali = $order->getShippingAmount() + ($order->getShippingAmount() * ($iva / 100));
                        $arrayCOR[$countCOR][15] = number_format($spedizionePortali, 2, ".", "");
                        if ($flagStato == "IT") {
                            $arrayCOR[$countCOR][16] = number_format($iva, 0, ".", "");
                            $imponibileSped = round($order->getShippingAmount(), 2);
                            $impostaSped = $imponibileSped * $iva / 100;
                        } else if ($flagStato == "UE") {
                            $arrayCOR[$countCOR][16] = "22UE";
                            $imponibileSped = round($order->getShippingAmount(), 2);
                            $impostaSped = $imponibileSped * $iva / 100;
                        } else if ($flagStato == "E") {
                            $arrayCOR[$countCOR][16] = "NI08";
                            $imponibileSped = round($spedizionePortali, 2);
                            $impostaSped = 0;
                        }

                        $arrayCOR[$countCOR][17] = number_format($imponibileSped, 2, ".", "");
                        $arrayCOR[$countCOR][18] = number_format($impostaSped, 2, ".", "");
                    } else {
                        $arrayCOR[$countCOR][15] = number_format($order->getShippingAmount(), 2, ".", "");
                        if ($flagStato == "IT") {
                            $arrayCOR[$countCOR][16] = number_format($iva, 0, ".", "");
                            $imponibileSped = round($order->getShippingAmount(), 2) / (($iva + 100) / 100);
                            $impostaSped = $imponibileSped * $iva / 100;
                        } else if ($flagStato == "UE") {
                            $arrayCOR[$countCOR][16] = "22UE";
                            $imponibileSped = round($order->getShippingAmount(), 2) / (($iva + 100) / 100);
                            $impostaSped = $imponibileSped * $iva / 100;
                        } else if ($flagStato == "E") {
                            $arrayCOR[$countCOR][16] = "NI08";
                            $imponibileSped = round($order->getShippingAmount(), 2);
                            $impostaSped = 0;
                        }

                        $arrayCOR[$countCOR][17] = number_format($imponibileSped, 2, ".", "");
                        $arrayCOR[$countCOR][18] = number_format($impostaSped, 2, ".", "");
                    }

                    $arrayCOR[$countCOR][19] = "Spese Trasporto";
                    $arrayCOR[$countCOR][20] = "3C_001";
                    $arrayCOR[$countCOR][21] = $codiceCommessa;
                    $arrayCOR[$countCOR][22] = "010120";
                    $arrayCOR[$countCOR][23] = "010120";
                    $arrayCOR[$countCOR][24] = $dataInizioMese;
                    $arrayCOR[$countCOR][25] = $dataFineMese;
                    if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata") {
                        $arrayCOR[$countCOR][26] = "42900130";  // codice conto banca fisso
                    }
                    else if ($order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                        $arrayCOR[$countCOR][26] = "57100060";
                    }
                    else {
                        $arrayCOR[$countCOR][26] = "42900120";  // codice conto banca fisso
                    }
                    $arrayCOR[$countCOR][27] = "1";
                    if ($order->getCustomerGroupId() == 5 || $order->getCustomerGroupId() == 4 || $order->getPayment()->getMethodInstance()->getTitle() == "M2E Pro Payment") {
                        $spedizionePortali = $order->getShippingAmount() + ($order->getShippingAmount() * ($iva / 100));
                        $arrayCOR[$countCOR][28] = number_format($spedizionePortali, 2, ".", "");
                    } else {
                        $arrayCOR[$countCOR][28] = number_format($order->getShippingAmount(), 2, ".", "");
                    }
                    $arrayCOR[$countCOR][29] = "0";
                    $arrayCOR[$countCOR][30] = "Incasso Corrispettivi";

                    $countCOR = $countCOR + 1;
                } else {
                    // recupero spese di spedizione standard per paese
                    $countryCode = $order->getShippingAddress()->getCountryId();
                    $iso3 = Mage::getModel('directory/country')->load($countryCode)->getIso3Code();

                    $stringQuery = "select prezzo from " . $resource->getTableName('spedizioni') . " where paese='" . $iso3 . "'";
                    $spedizione = $readConnection->fetchOne($stringQuery);
                    if ($spedizione==null) {
                        $spedizione = 50;
                    }


                    $arrayODV[$countODV][0] = "TES";
                    $arrayODV[$countODV][1] = "701";
                    $arrayODV[$countODV][2] = $dateOrder;
                    $arrayODV[$countODV][3] = "123";
                    $arrayODV[$countODV][4] = $order->getIncrementId();
                    $arrayODV[$countODV][5] = $codiceCliente;
                    $arrayODV[$countODV][6] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                    $arrayODV[$countODV][7] = "1";
                    $arrayODV[$countODV][8] = "SPETRA";
                    $arrayODV[$countODV][9] = "Spese Trasporto Rif Ord N. " . $order->getIncrementId();
                    $arrayODV[$countODV][10] = $dateOrder;
                    $arrayODV[$countODV][11] = "1";
                    $arrayODV[$countODV][12] = number_format($spedizione, 2, ".", "");
                    $arrayODV[$countODV][13] = $codiceCommessa;

                    $countODV = $countODV + 1;


                    $arrayODV[$countODV][0] = "RIG";
                    $arrayODV[$countODV][1] = "701";
                    $arrayODV[$countODV][2] = $dateOrder;
                    $arrayODV[$countODV][3] = "123";
                    $arrayODV[$countODV][4] = $order->getIncrementId();
                    $arrayODV[$countODV][5] = $codiceCliente;
                    $arrayODV[$countODV][6] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                    $arrayODV[$countODV][7] = "1";
                    $arrayODV[$countODV][8] = "SPETRA";
                    $arrayODV[$countODV][9] = "Spese Trasporto Rif Ord N. " . $order->getIncrementId();
                    $arrayODV[$countODV][10] = $dateOrder;
                    $arrayODV[$countODV][11] = "1";
                    $arrayODV[$countODV][12] = number_format($spedizione, 2, ".", "");
                    $arrayODV[$countODV][13] = $codiceCommessa;

                    $countODV = $countODV + 1;

                }

                // spese bancarie da inserire nel file ODV
                // Paypal 3,5%
                // Carta di credito o prepagata 1,55%
                // Carte cinese 1,90%
                if ($countODV == 0) {
                    $arrayODV[$countODV][0] = "TES";
                    $arrayODV[$countODV][1] = "701";
                    $arrayODV[$countODV][2] = $dateOrder;
                    $arrayODV[$countODV][3] = "123";
                    $arrayODV[$countODV][4] = $order->getIncrementId();
                    $arrayODV[$countODV][5] = $codiceCliente;
                    $arrayODV[$countODV][6] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                    $arrayODV[$countODV][7] = "1";
                    $arrayODV[$countODV][8] = "SPEBAN";
                    $arrayODV[$countODV][9] = "Spese Banca Rif Ord N. " . $order->getIncrementId();
                    $arrayODV[$countODV][10] = $dateOrder;
                    $arrayODV[$countODV][11] = "1";


                    // controllo metodo di pagamento
                    if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata" || $order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                        if ($flagStato == "IT" || $flagStato == "UE") {
                            $totale = $order->getGrandTotal();
                            $prezzo = ($totale * $speseCartaEur / 100) + $tariffaCarta;
                        } else if ($flagStato == "E") {
                            $totale = $order->getGrandTotal();
                            $prezzo = ($totale * $speseCartaNoEur / 100) + $tariffaCarta;
                        }
                    } else {
                        $totale = $order->getGrandTotal()-$order->getTotalRefunded();
                        $prezzo = ($totale * $spesePaypal / 100) + $tariffaPaypal;
                    }


                    $arrayODV[$countODV][12] = number_format($prezzo, 2, ".", "");
                    $arrayODV[$countODV][13] = $codiceCommessa;

                    $countODV = $countODV + 1;
                }

                $arrayODV[$countODV][0] = "RIG";
                $arrayODV[$countODV][1] = "701";
                $arrayODV[$countODV][2] = $dateOrder;
                $arrayODV[$countODV][3] = "123";
                $arrayODV[$countODV][4] = $order->getIncrementId();
                $arrayODV[$countODV][5] = $codiceCliente;
                $arrayODV[$countODV][6] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                $arrayODV[$countODV][7] = "1";
                $arrayODV[$countODV][8] = "SPEBAN";
                $arrayODV[$countODV][9] = "Spese Banca Rif Ord N. " . $order->getIncrementId();
                $arrayODV[$countODV][10] = $dateOrder;
                $arrayODV[$countODV][11] = "1";


                // controllo metodo di pagamento
                if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata" || $order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                    if ($flagStato == "IT" || $flagStato == "UE") {
                        $totale = $order->getGrandTotal();
                        $prezzo = ($totale * $speseCartaEur / 100) + $tariffaCarta;
                    } else if ($flagStato == "E") {
                        $totale = $order->getGrandTotal();
                        $prezzo = ($totale * $speseCartaNoEur / 100) + $tariffaCarta;
                    }
                } else {
                    $totale = $order->getGrandTotal()-$order->getTotalRefunded();
                    $prezzo = ($totale * $spesePaypal / 100) + $tariffaPaypal;
                }


                $arrayODV[$countODV][12] = number_format($prezzo, 2, ".", "");
                $arrayODV[$countODV][13] = $codiceCommessa;

                $countODV = $countODV + 1;


                $arrayCOR[$countCOR][0] = "GEN";
                $arrayCOR[$countCOR][1] = "0";
                $arrayCOR[$countCOR][2] = "701";
                $arrayCOR[$countCOR][3] = $anno;
                $arrayCOR[$countCOR][4] = $dateOrder;
                $arrayCOR[$countCOR][5] = "123";
                $arrayCOR[$countCOR][6] = "";
                $arrayCOR[$countCOR][7] = $dateOrder;
                $arrayCOR[$countCOR][8] = $order->getIncrementId();
                $arrayCOR[$countCOR][9] = "1";
                $arrayCOR[$countCOR][10] = "eCommerce Rif Ord N. " . $order->getIncrementId();
                $arrayCOR[$countCOR][11] = $anno;
                $arrayCOR[$countCOR][12] = $mese;
                $arrayCOR[$countCOR][13] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                $arrayCOR[$countCOR][14] = $codiceConto;

                $arrayCOR[$countCOR][15] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                if ($flagStato == "IT") {
                    $arrayCOR[$countCOR][16] = number_format($iva, 0, ".", "");
                    $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2) / (($iva + 100) / 100);
                    $imposta = $imponibile * $iva / 100;
                } else if ($flagStato == "UE") {
                    $arrayCOR[$countCOR][16] = "22UE";
                    $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2) / (($iva + 100) / 100);
                    $imposta = $imponibile * $iva / 100;
                } else if ($flagStato == "E") {
                    $arrayCOR[$countCOR][16] = "NI08";
                    $imponibile = round($order->getGrandTotal()-$order->getTotalRefunded(), 2);
                    $imposta = 0;
                }


                $arrayCOR[$countCOR][17] = number_format($imponibile, 2, ".", "");
                $arrayCOR[$countCOR][18] = number_format($imposta, 2, ".", "");

                $arrayCOR[$countCOR][19] = "Incasso";
                $arrayCOR[$countCOR][20] = "3C_001";
                $arrayCOR[$countCOR][21] = $codiceCommessa;
                $arrayCOR[$countCOR][22] = "010120";
                $arrayCOR[$countCOR][23] = "010120";
                $arrayCOR[$countCOR][24] = $dataInizioMese;
                $arrayCOR[$countCOR][25] = $dataFineMese;
                if ($order->getPayment()->getMethodInstance()->getTitle() == "Carta di credito o prepagata") {
                    $arrayCOR[$countCOR][26] = "42900130";  // codice conto banca fisso
                }
                else if ($order->getPayment()->getMethodInstance()->getTitle() == "Bank Transfer") {
                    $arrayCOR[$countCOR][26] = "57100060";
                }
                else {
                    $arrayCOR[$countCOR][26] = "42900120";  // codice conto banca fisso
                }
                $arrayCOR[$countCOR][27] = "1";
                $arrayCOR[$countCOR][28] = number_format($order->getGrandTotal()-$order->getTotalRefunded(), 2, ".", "");
                $arrayCOR[$countCOR][29] = "0";
                $arrayCOR[$countCOR][30] = "Incasso Corrispettivi";

                $countCOR = $countCOR + 1;

                $totaleOrdine=round($order->getGrandTotal() - $order->getTotalRefunded(), 2);
                $shipping=$order->getShippingAmount();
                $sommaProdotti=$sommaProdotti+$shipping;
                $sommaProdotti=round($sommaProdotti,2);
                if (($sommaProdotti)!=$totaleOrdine){


                    $differenza=$totaleOrdine-$sommaProdotti;


                    $arrayCOR[1][15] = $arrayCOR[1][15]+$differenza;
                    if ($flagStato == "IT") {
                        $imponibile = round($arrayCOR[1][15], 2) / (($iva + 100) / 100);
                        $imposta = $imponibile * $iva / 100;
                    } else if ($flagStato == "UE") {
                        $imponibile = round($arrayCOR[1][15], 2) / (($iva + 100) / 100);
                        $imposta = $imponibile * $iva / 100;
                    } else if ($flagStato == "E") {
                        $imponibile = round($arrayCOR[1][15], 2);
                        $imposta = 0;
                    }

                    $arrayCOR[1][17] = number_format($imponibile, 2, ".", "");
                    $arrayCOR[1][18] = number_format($imposta, 2, ".", "");
                    $arrayCOR[1][28] = number_format($arrayCOR[1][15], 2, ".", "");
                }

                // inserirsco gli array per ODA, ODV e CORR in stringhe con le giuste formattazioni
                $stringaCOR = "";
                $stringaODA = "";
                $stringaODV = "";
                for ($p = 0; $p < count($arrayCOR); $p++) {
                    for ($m = 0; $m < 31; $m++) {
                        if ($m != 0) {
                            $stringaCOR .= ",";
                        }
                        $stringaCOR .= $arrayCOR[$p][$m];
                    }
                    if ($p != count($arrayCOR) - 1) {
                        $stringaCOR .= "\r\n";
                    }
                }


                for ($p = 0; $p < count($arrayODA); $p++) {
                    for ($m = 0; $m < 17; $m++) {
                        if ($m != 0) {
                            $stringaODA .= ",";
                        }
                        $stringaODA .= $arrayODA[$p][$m];
                    }
                    if ($p != count($arrayODA) - 1) {
                        $stringaODA .= "\r\n";
                    }
                }


                for ($p = 0; $p < count($arrayODV); $p++) {
                    for ($m = 0; $m < 14; $m++) {
                        if ($m != 0) {
                            $stringaODV .= ",";
                        }
                        $stringaODV .= $arrayODV[$p][$m];
                    }
                    if ($p != count($arrayODV) - 1) {
                        $stringaODV .= "\r\n";
                    }
                }

                // stampo i file ODA,ODV e CORR
                file_put_contents($path . $COR, $stringaCOR);

                file_put_contents($path . $ODA, $stringaODA);

                file_put_contents($path . $ODV, $stringaODV);


                $readConnection->closeConnection();

            }




        }


        return $this;
			
	}
	
   
}

