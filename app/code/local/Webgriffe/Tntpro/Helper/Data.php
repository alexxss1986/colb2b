<?php

class Webgriffe_Tntpro_Helper_Data extends Mage_Core_Helper_Data
{

    protected $APPLICATION = array(
        True => "MYRTL",
        False => "MYRTLI",
    );
    protected $REVERTED_SERVICE_TABLE; #creata nel costruttore a partire dalla seguente
    protected $SERVICE_TABLE = array(
        array("Descrizione" => "Economy Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "48N"),
        array("Descrizione" => "9:00 Express", "Doc/Non" => "DOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "09D"),
        array("Descrizione" => "9:00 Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "09N"),
        array("Descrizione" => "10:00 Express", "Doc/Non" => "DOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "10D"),
        array("Descrizione" => "10:00 Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "10N"),
        array("Descrizione" => "12:00 Express", "Doc/Non" => "DOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "12D"),
        array("Descrizione" => "12:00 Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "12N"),
        array("Descrizione" => "Express", "Doc/Non" => "DOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "15D"),
        array("Descrizione" => "Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "15N"),
        array("Descrizione" => "12:00 Economy Express", "Doc/Non" => "NONDOC", "Dom/Int" => "INT", "Divisione" => "G", "Servizio" => "412"),
        array("Descrizione" => "Economy Express Road SCS (Buste)", "Doc/Non" => "DOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "N"),
        array("Descrizione" => "Economy Express Road SCS (Colli)", "Doc/Non" => "NONDOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "N"),
        array("Descrizione" => "10:00 Express (Buste)", "Doc/Non" => "DOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "D"),
        array("Descrizione" => "10:00 Express (Colli)", "Doc/Non" => "NONDOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "D"),
        array("Descrizione" => "12:00 Express (Buste)", "Doc/Non" => "DOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "T"),
        array("Descrizione" => "12:00 Express (Colli)", "Doc/Non" => "NONDOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "T"),
        array("Descrizione" => "Express (Buste)", "Doc/Non" => "DOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "A"),
        array("Descrizione" => "Express (Colli)", "Doc/Non" => "NONDOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "A"),
        array("Descrizione" => "Express Air SCS (Buste)", "Doc/Non" => "DOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "A"),
        array("Descrizione" => "Express Air SCS (Colli)", "Doc/Non" => "NONDOC", "Dom/Int" => "DOM", "Divisione" => "D", "Servizio" => "A"),
    );
    protected $ADDRESS_TYPE = array(
        "S" => "Sender",
        "R" => "Receiver",
        "D" => "Delivery",
        "C" => "Collection",
    );
    protected $ITEM_TYPE = array(
        "S" => "Buste",
        "B" => "Bauletti Piccoli",
        "D" => "Bauletti Grandi",
        "C" => "Colli",
    );
    protected $ITEM_TYPE_MAP = array(
        "S" => "DOC",
        "B" => "NONDOC",
        "D" => "NONDOC",
        "C" => "NONDOC",
    );
    protected $ACTION = array(
        "I" => "Inserimento nuova spedizione",
        "M" => "Modifica",
        "D" => "Cancellazione",
        "R" => "Ristampa",
    );
    protected $ATTRIBUTI_CONSIGNMENT = array(#i valori sono i default
        'action' => 'I', 'internazionale' => 'N', 'insurance' => 'N', 'hazardous' => 'N',
        'cashondelivery' => 'N', 'codcommission' => 'S', 'insurancecommission' => 'S', #S,R => Sender, Receiver
        'operationaloption' => 0, 'highvalue' => 'N', 'specialgoods' => 'N',
    );
    protected $OPERATIONAL_OPTION = array(
        0 => "Consegna all'indirizzo",
        1 => "Fermo deposito TNT",
        2 => "Consegna programmata",
        3 => "Fermo TNT Point"
    );
    protected $INTERNATIONAL_OPTIONS = array(
        "FDA" => "US Food & Drug Administration",
        "PR" => "Priority",
        "IN" => "Insurance",
        "SE" => "Self Brought to Depot",
    );

    public function __construct($addresswrapper = "wgtntpro/addresswrapper")
    {
        $this->_addresswrapper = $addresswrapper;

        $this->REVERTED_SERVICE_TABLE = array();
        foreach ($this->SERVICE_TABLE as $line) {
            $this->REVERTED_SERVICE_TABLE[$line["Servizio"]] = $line;
        }
    }

    public function generateXml($obj, $magazzino_default, $magazzino, $params, $b_domestic)
    {
        return $this->_generateXml($obj, $magazzino_default, $magazzino, $params, $b_domestic);
    }

    protected function _addCData($parent, $name, $value)
    {
        $child = $parent->addChild($name);
        $node = dom_import_simplexml($child);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($value));
    }

    protected function _createAddressWrapper($address)
    {
        if (strpos($this->_addresswrapper, '/') === false) {
            $ret = new $this->_addresswrapper;
        } else {
            $ret = Mage::helper($this->_addresswrapper);
        }
        return $ret->init($address);
    }

    protected function _generateXml($obj, $magazzino_default, $magazzino, $params, $b_domestic)
    {
        $_order = $obj->getOrder();
        $_addresses = array(
            "S" => $magazzino_default,
            "C" => $magazzino,
            "R" => $this->_createAddressWrapper($_order->getBillingAddress()),
            "D" => $this->_createAddressWrapper($_order->getShippingAddress()),
        );
        $configs = Mage::getStoreConfig('shipping/wgtntpro');

        if ($b_domestic) {
            $domestic = TRUE;
            $international = FALSE;
        } else {
            $domestic = FALSE;
            $international = TRUE;
        }

        $shipment = new SimpleXMLElement('<shipment />');
        $shipment->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $shipment->addAttribute('xsi:noNamespaceSchemaLocation', 'file:///q:/DTD/xsd/routinglabel.xsd');
        $software = $shipment->addChild('software');
        $application = $software->addChild('application', $this->APPLICATION[$b_domestic]);
        $version = $software->addChild('version', $configs['version']);
        $security = $shipment->addChild('security');
        $customer = $security->addChild('customer', $configs['customer']);
        $user = $security->addChild('user', $configs['user']);
        $password = $security->addChild('password', $configs['password']);
        $langid = $security->addChild('langid', $configs['langid']);

        # consignment
        $consignment = $shipment->addChild('consignment');
        $params['internazionale'] = $international ? 'Y' : 'N';
        foreach ($this->ATTRIBUTI_CONSIGNMENT as $k => $v) {
            if (isset($params [$k])) {
                $consignment->addAttribute($k, $params [$k]);
            } else {
                $consignment->addAttribute($k, $v);
            }
        }


        $senderAccId = $consignment->addChild('senderAccId', $magazzino->getSenderAccId());
        $consignmenttype = $consignment->addChild('consignmenttype', 'T');
        #<PrintInstrDocs>N</PrintInstrDocs>
        $PrintInstrDocs = $consignment->addChild('PrintInstrDocs', 'Y'==$params['PrintInstrDocs'] ? 'Y' : 'N' );
        $division = $consignment->addChild('division', $this->REVERTED_SERVICE_TABLE[$params['product']]['Divisione']);

        $params = $this->_sanitizeCheckboxValues($params);

        foreach ($params as $k => $v) {
            if (in_array($k, array('invoicevalue', 'invoicecurrency'))) { #check della spunta su invoice
                if ('Y' != $params['invoice']) { # se non è spuntato non setto i valori
                    continue;
                }
            }
            if (in_array($k, array('insurancevalue', 'insurancecurrency'))) { #check della spunta su insurance
                if ('Y' != $params['insurance']) { # se non è spuntato non setto i valori
                    continue;
                }
            }
            if (in_array($k, array('insurancevalue', 'invoicevalue', 'codfvalue'))) { #valori a 2 decimali
                $v = str_pad(intval($v * 100), 13, "0", STR_PAD_LEFT);
            }
            if (in_array($k, array('actualweight', 'actualvolume'))) { #valori a 3 decimali
                $v = str_pad(intval($v * 1000), 'actualweight' == $k ? 8 : 7, "0", STR_PAD_LEFT);
            }
            if ((!is_array($v)) && (!in_array($k, array('usalo', 'magazzino'))) && (!array_key_exists($k, $this->ATTRIBUTI_CONSIGNMENT))) {
                $this->_addCData($consignment, $k, $v);
            }
        }

        if (isset($params['options'])) {
            $options = $consignment->addChild('options');
            foreach ($params['options'] as $v) {
                $options->addChild('option', $v);
            }
        }

        $systemcode = $consignment->addChild('systemcode', 'RL');
        $systemversion = $consignment->addChild('systemversion', '1.0');

        #INDIRIZZI
        $addresses = $consignment->addChild('addresses');
        foreach ($_addresses as $k => $_address) {
            $address = $addresses->addChild('address');
            $address->addChild('addressType', $k);
            foreach (array('vatno', 'addrline1', 'postcode', 'phone1', 'phone2', 'name',
        'country', 'town', 'contactname', 'province', 'email') as $campo) {
                $this->_addCData($address, $campo, $_address->getDataUsingMethod($campo));
            }
        }

        #$collectiontrg = $consignment->addChild('collectiontrg');

        $dimensions = $consignment->addChild('dimensions');
        $dimensions->addAttribute('itemaction', 'I');
        $dimensions->addChild('itemtype', 'C');
        $dimensions->addChild('volume', str_pad(intval($params['actualvolume'] * 1000 / $params['totalpackages']), 7, '0', STR_PAD_LEFT));
        $dimensions->addChild('weight', str_pad(intval($params['actualweight'] * 1000 / $params['totalpackages']), 8, '0', STR_PAD_LEFT));
        $dimensions->addChild('quantity', $params['totalpackages']);

        if ($configs['debug']) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($shipment->asXML());
            return $dom->saveXML();
        } else {
            return $shipment->asXML();
        }
    }

    /**
     *
     * @param Webgriffe_Tntpro_Model_Consignmentno $consigment_number_model
     */
    public function cancelXML($consigment_number_model)
    {
        $configs = Mage::getStoreConfig('shipping/wgtntpro');

        $shipment = new SimpleXMLElement('<shipment />');
        $shipment->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $shipment->addAttribute('xsi:noNamespaceSchemaLocation', 'file:///q:/DTD/xsd/routinglabel.xsd');
        $software = $shipment->addChild('software');
        $application = $software->addChild('application', $this->APPLICATION[$consigment_number_model->getDomestic()]);
        $version = $software->addChild('version', $configs['version']);
        $security = $shipment->addChild('security');
        $customer = $security->addChild('customer', $configs['customer']);
        $user = $security->addChild('user', $configs['user']);
        $password = $security->addChild('password', $configs['password']);
        $langid = $security->addChild('langid', $configs['langid']);

        $consignment = $shipment->addChild('consignment');
        $consignment->addAttribute('internazionale', $consigment_number_model->getDomestic() ? 'N' : 'Y');
        $consignment->addAttribute('action', 'D'); #cancellazione
        $consignmenttype = $consignment->addChild('consignmentno', $consigment_number_model->getId());

        if ($configs['debug']) {
            $dom = new DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($shipment->asXML());
            return $dom->saveXML();
        } else {
            return $shipment->asXML();
        }
    }

    public function soapCall($xml)
    {
        $wsdl = Mage::getStoreConfig('shipping/wgtntpro/wsdl');
        $client = new Zend_Soap_Client($wsdl, array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
        $temp = new stdClass();
        $temp->inputXml = $xml;
        try {
            $response = $client->getPDFLabel($temp); #chiamata soap
            $response_xml = new SimpleXMLElement($response->getPDFLabelReturn->outputString);
            //$documentCorrect = $response->getPDFLabelReturn->documentCorrect;
            if (isset($response_xml->Complete)) {
                $ret = true;
            } else {
                $ret = (string)$response_xml->Incomplete->RuntimeError->Message.(string)$response_xml->Complete->WarningMessage;
            }
        } catch (Exception $e) {
            $ret = Mage::helper('wgtntpro')->__('WGTNT PRO: Soal Call Failed');
        }
        return $ret;
    }

    public function inDebug()
    {
        return Mage::getStoreConfig('shipping/wgtntpro/debug');
    }

    public function isDefaultEnabled(){
        return Mage::getStoreConfig('shipping/wgtntpro/default_enabled');
    }

    public function toServiceOptionArray($package_type = "C")
    {
        $ret = array();

        foreach ($this->SERVICE_TABLE as $rec) {
            if ($this->ITEM_TYPE_MAP[$package_type] == $rec["Doc/Non"]) {
                $row = array('value' => $rec["Servizio"], 'label' => $rec["Descrizione"], 'extra' => '', 'class' => $rec['Dom/Int']);
                $ret[] = $row;
            }
        }
        return $ret;
    }

    public function toOperationalOptionArray()
    {
        $ret = array();
        foreach ($this->OPERATIONAL_OPTION as $k => $v) {
            $row = array('value' => $k, 'label' => $this->__($v));
            $ret[] = $row;
        }
        return $ret;
    }

    public function toInternationalOptionArray()
    {
        $ret = array();
        foreach ($this->INTERNATIONAL_OPTIONS as $k => $v) {
            $row = array('value' => $k, 'label' => $this->__($v));
            $ret[] = $row;
        }
        return $ret;
    }

    public function thisTest()
    {
        return True;
    }

    public function generateManifestCSV( $consignmentno, $delimiter = ';' ) {
//Pos. Lettera Di Vettura 1 : 1
//Pos. Peso [grammi] 1 : 18
//Pos. Volume [m3*100] 1 : 20
//Pos. Numero Colli 1 : 17
//Pos. Servizio 1 : 21
//Pos. Riferimento Mittente 1 : 3
//Pos. Istruz.Operative 1 : 22
//Pos. Descrizione Merce 1 : 23
//Pos. Indirizzo Mittente 1 1 : 6
//Pos. CAP MIttente 1 : 7
//Pos. Rag. Soc. Mittente 1 : 5
//Pos. Città Mittente 1 : 8
//Pos. Provincia Mittente 1 : 9
//Pos. Indirizzo Destinatario1 1 : 12
//Pos. CAP Destinatario 1 : 13
//Pos. Rag. Soc. Destinatario 1 : 11
//Pos. Città Destinatario 1 : 14
//Pos. Provincia Destinatario 1 : 15
        $campi = array ( 0 => 'LetteraDiVettura', 1 => 'DataDiPartenza', 2 => 'RiferimentoMittente',
            3 => 'CodiceCliente', 4 => 'Mittente', 5 => 'IndMittente', 6 => 'CapMittente',
            7 => 'LocalitaMittente', 8 => 'ProvMittente', 9 => 'FilPartenza', 10 => 'Destinatario',
            11 => 'IndDestinatario', 12 => 'CapDestinatario', 13 => 'LocalitaDestinatario',
            14 => 'ProvDestinatario', 15 => 'FilArrivo', 16 => 'Colli', 17 => 'Peso',
            18 => 'PesoRilevato', 19 => 'Volume', 20 => 'TipoServizio', 21 => 'Contrassegno',
            22 => 'DescrizioneMerce', 23 => 'Status', 24 => 'DataConsegna', 25 => 'Ricevente',
            26 => 'Datainiziogiac', 27 => 'Numgiac', 28 => 'Motivazione', 29 => 'Datasvincolo',
            30 => 'Costo', );
        $fp = fopen("php://memory", "rw");

        fputcsv($fp, $fields, $delimiter);
        $coll = Mage::getModel('wgtntpro/consignmentno')->getCollection();
        $coll -> addFieldToFilter('consignmentno', array('IN' => $consignmentno) );
        $coll -> load();
        $primo = true;
        $contatore_indirizzo = 1;
        foreach ($coll as $item) {
            $fields = array();
            $xml = new SimpleXMLElement($item->getXmlResponse());
            $xmlc = $xml->Complete;
            if ($primo) {
                fputcsv($fp, $campi , $delimiter);
                $primo = false;
            }

            $fields[] = (string)$xmlc->TNTConNo;
            $date = (string)$xmlc->Date;
            $fields[] = substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
            $fields[] = (string)$xmlc->SenderReference;
            $fields[] = (string)$xmlc->AccountNo;
            $fields[] = (string)$xmlc->SenderName;
            $fields[] = (string)$xmlc->SndAddress;
            $fields[] = (string)$xmlc->SndZIPCode;
            $fields[] = (string)$xmlc->SndTown;
            $fields[] = (string)$xmlc->SndProvince;
            $fields[] = (string)$xmlc->OriginDepotID;
            $contatore = 0;
            foreach ($xmlc->children() as $child) {
                $name = $child->getName();
                if ('IAddress' == $name) {
                    if (2 == $child->IAddressType) {
                        $fields[] = (string)$child->IName;
                        $fields[] = ((string)$child->IAddressL1).((string)$child->IAddressL2).((string)$child->IAddressL3);
                        $fields[] = (string)$child->IZIPCode;
                        $fields[] = (string)$child->ITown;
                        $fields[] = (string)$child->IProvince;
                        break;
                    }
                }
            }
            #FilArrivo
            $fields[] = ((string)$xmlc->DestinationDepot->DepotID).' '.((string)$xmlc->DestinationDepot->DepotName).' Destination-Hub '.
                        ((string)$xmlc->DestinationHUB).' Microzone '.((string)$xmlc->Microzone);
            #Colli
            $fields[] = (string)$xmlc->ItemNo;
            $fields[] = (string)$xmlc->Weight;
            $fields[] = ''; # peso rilevato
            $fields[] = (string)$xmlc->Volume;
            $fields[] = (string)$xmlc->ServiceDescr;
            $fields[] = ((string)$xmlc->CODValue)>0 ? 'SI' : 'NO';
            $fields[] = (string)$xmlc->GoodsDesc;


//            foreach ($xmlc->children() as $child) {
//                $name = $child->getName();
//                if ($name=='IAddress') {
//                    foreach ($child->children() as $grandchildren) {
//                        $fields[] = (string)$grandchildren;
//                    }
//                } elseif ('DestinationDepot'==$name) {
//                    foreach ($child->children() as $grandchildren) {
//                        $fields[] = (string)$grandchildren;
//                    }
//                } else {
//                    $fields[] = (string)$child;
//                }
//            }
            fputcsv($fp, $fields , $delimiter);
        }
        fseek($fp, 0);
        $ret = '';
        while (!feof($fp))  {
            $ret .=fread($fp, 8192);
        }
        fclose($fp);
        return $ret;
    }


    public function sendMail( $csv ) {
        $mail = new Zend_Mail('utf-8');
        $mail -> createAttachment($csv, Zend_Mime::TYPE_OCTETSTREAM,Zend_Mime::DISPOSITION_ATTACHMENT,Zend_Mime::ENCODING_BASE64,'consignment.csv');

        $mail -> setFrom ( Mage::getStoreConfig('shipping/wgtntpro/mailmittente'), "");
        $mail -> setReplyTo ( Mage::getStoreConfig('shipping/wgtntpro/mailmittente'), "");
        $mail -> setSubject( Mage::getStoreConfig('shipping/wgtntpro/mailsubject') );
        $mail -> addTo ( Mage::getStoreConfig('shipping/wgtntpro/maildestinatario') );

        $mail -> setBodyText('');
        try {
            $mail = $mail -> send();
        } catch (Zend_Mail_Transport_Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $params
     * @return array
     */
    private function _sanitizeCheckboxValues($params)
    {
        $defaultCheckboxValues = array(
            'invoice' => 'N',
            'insurance' => 'N',
        );
        return array_merge($defaultCheckboxValues, $params);
    }

}