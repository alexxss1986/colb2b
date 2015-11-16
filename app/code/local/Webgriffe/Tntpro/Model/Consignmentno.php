<?php

class Webgriffe_Tntpro_Model_Consignmentno extends Mage_Core_Model_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function _construct()
    {
        $this->_init('wgtntpro/consignmentno');
    }

    public function validate()
    {
        $errors = array();
        #TODO implementare un controllo di errori
        return $errors;
    }

    /**
     * Analyze given response object and return proper result.
     * 
     * @param SimpleXMLElement $response_xml
     * @return boolean
     */
    public function storeConsignmentno($response_xml) {
        $go = false;
        if (isset($response_xml->Complete->TNTConNo)) {
            $this->setConsignmentno($response_xml->Complete->TNTConNo);
            $go = True;
        } elseif (isset($response_xml->Incomplete->ConsignmentNo) && 
                !empty($response_xml->Incomplete->ConsignmentNo)) {
            $this->setConsignmentno($response_xml->Incomplete->ConsignmentNo);
            $go = True;
        }
        return $go;
    }
    
    public function store($xml_soap, $id_magazzino, $domestic)
    {
        $response_xml = new SimpleXMLElement($xml_soap->getPDFLabelReturn->outputString);
        if ($this->storeConsignmentno($response_xml)) {
            $this->setOk($xml_soap->getPDFLabelReturn->documentCorrect);
            $this->setTrack('http://www.tnt.it/tracking/getTrack?WT=1&ldv='.$this->getConsignmentno());
            $this->setPost(serialize($_POST));
            $this->setXmlResponse($xml_soap->getPDFLabelReturn->outputString);
            $this->setBinaryDocument($xml_soap->getPDFLabelReturn->binaryDocument);
            $this->setDomestic($domestic);
            $this->setFkMagazzinoId($id_magazzino);
            $date = Mage::getModel('core/date')->gmtDate();
            $this->setCreatedAt($date);
            $this->save();
        }
        return $this;
    }

}

