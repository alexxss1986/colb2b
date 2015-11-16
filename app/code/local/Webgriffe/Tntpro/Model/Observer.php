<?php

class Webgriffe_Tntpro_Model_Observer
{
    const LOGFILE = 'Webgriffe_Tntpro.log';

    public function before($observer)
    {
        $debug = $this->_getDebugConfig();
        Mage::log("Called " . __METHOD__, null, self::LOGFILE, $debug);
        $obj = $observer->getDataObject();
        if ($obj) {
            $params = Mage::app()->getRequest()->getParam('wgtntpro');
            Mage::log("Request params: " . PHP_EOL . print_r($params, true), null, self::LOGFILE, $debug);
            if (isset($params['usalo'])) { #modulo usato
                $documentCorrect         = False;

                /** @var Webgriffe_Tntpro_Helper_Data $helper */
                $helper     = Mage::helper('wgtntpro');
                $msg        = $helper->__('Tnt failed.');
                $magazzino_default = Mage::getModel('wgtntpro/magazzini');
                $magazzino_default -> loadDefault();
                /** @var Webgriffe_Tntpro_Model_Magazzini $magazzino */
                $magazzino  = Mage::getModel('wgtntpro/magazzini')->load($params['magazzino']);
                $countryTo  = $obj->getOrder()->getShippingAddress()->getCountryId();
                $b_domestic = ($magazzino->getCountry() == $countryTo) ||
                    ('IT'==$magazzino->getCountry() && ('SM'==$countryTo || 'VA'==$countryTo)); //caso speciale san marino/vatican city da italia

                Mage::log(PHP_EOL . '$magazzino_default ' . print_r($magazzino_default->debug(), true), null, self::LOGFILE, $debug);
                Mage::log(PHP_EOL . '$magazzino ' . print_r($magazzino->debug(), true), null, self::LOGFILE, $debug);

                $xml = $helper->generateXml($obj, $magazzino_default, $magazzino, $params, $b_domestic);

                if ($debug) {
                    if (!$helper->thisTest()) {
                        Mage::throwException($helper->__('Tnt TEST failed.'));
                    } else {
                        Mage::getSingleton('adminhtml/session')->addSuccess('<pre>' . htmlspecialchars($xml) . '</pre>');
                    }
                }

                #PARTE SOAP
                $wsdl = Mage::getStoreConfig('shipping/wgtntpro/wsdl');
                Mage::log("Start logging soap call to $wsdl", null, self::LOGFILE, $debug);
                $client = new Zend_Soap_Client($wsdl, array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
                $temp = new stdClass();
                $temp->inputXml = $xml;
                Mage::log("XML request body: " . PHP_EOL . $xml, null, self::LOGFILE, $debug);
                try {
                    /** @var DOMDocument $response */
                    $response = $client->getPDFLabel( $temp ); #chiamata soap
                    $response_xml = new SimpleXMLElement($response->getPDFLabelReturn->outputString);

                    Mage::log("XML response body: " . PHP_EOL . print_r($response, true), null, self::LOGFILE, $debug);

                    $documentCorrect = $response->getPDFLabelReturn->documentCorrect;
                } catch (Exception $e) {
                    Mage::log("Something went wrong while performing soap call: ".$e->getMessage(), null, self::LOGFILE, $debug);
                    $response = false;
                }
                #FINE PARTE SOAP
                if (False !== $response) {
                    Mage::getModel('wgtntpro/consignmentno')->store($response,$params['magazzino'], $b_domestic);
                    if ($debug) {
                        $dom = new DOMDocument('1.0');
                        $dom->preserveWhiteSpace = false;
                        $dom->formatOutput = true;
                        $dom->loadXML($response_xml->asXML());
                        Mage::getSingleton('adminhtml/session')->addSuccess('<hr /><pre>' . htmlspecialchars($dom->saveXML()) . '</pre>');
                    }
                    if ($documentCorrect) {
                        $obj->setShippingLabel($response->getPDFLabelReturn->binaryDocument);
                        Mage::app()->getRequest()->setParam('tracking_tnt', $response_xml->Complete->TNTConNo);
                        #verranno poi usati nell'after
                    } else {
                        #imposto l'errore dell'xml
                        /*Mage::getSingleton('adminhtml/session')->getData('messages')->clear();*/
                        Mage::throwException('TNT PRO: '.$response_xml->Incomplete->RuntimeError.' '.
                                $response_xml->Incomplete->RuntimeError->Message.' '.$response_xml->Incomplete->Message);
                    }
                } else {
                    Mage::getSingleton('adminhtml/session')->getData('messages')->clear();
                    Mage::throwException($helper->__('TNT PRO: Connection error to webservice'));
                }
            } else {
               #modulo non usato
            }
        }
        #Mage::throwException('TNT PRO: modalità di sviluppo');
        return $this;
    }

    public function after($observer)
    {
        $debug = $this->_getDebugConfig();
        Mage::log("Called " . __METHOD__, null, self::LOGFILE, $debug);
        $obj = $observer->getDataObject();
        if ($obj) {
            $params = Mage::app()->getRequest()->getParam('wgtntpro');
            Mage::log("Request params: " . PHP_EOL . print_r($params, true), null, self::LOGFILE, $debug);
            if (isset($params['usalo'])) {
                $track = Mage::app()->getRequest()->getParam('tracking_tnt');

                $orderShipmentTrack = Mage::getModel('sales/order_shipment_track')
                        ->setShipment($obj)
                        ->setData('title', Mage::getStoreConfig('carriers/wgtntpro/title') )
                        ->setData('number', $track)
                        ->setData('carrier_code', Webgriffe_Tntpro_Model_Track::CODE) #Mage_Sales_Model_Order_Shipment_Track::CUSTOM_CARRIER_CODE)
                        ->setData('order_id', $obj->getData('order_id'))
                        ->save();

                Mage::getModel('wgtntpro/consignmentno')
                        ->load($track)
                        ->setFkParentId($obj->getId())
                        ->save();
            }
        }
    }

    /**
     * @return mixed
     */
    protected function _getDebugConfig()
    {
        $debug = Mage::getStoreConfig('shipping/wgtntpro/debug');
        return $debug;
    }

}