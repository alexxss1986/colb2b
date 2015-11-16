<?php

/**
 * Class Webgriffe_Tntpro_Model_Magazzini
 * @method string getCountry()
 */
class Webgriffe_Tntpro_Model_Magazzini extends Mage_Core_Model_Abstract
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function _construct()
    {
        $this->_init('wgtntpro/magazzini');
    }

    public function validate()
    {
        $errors = array();
        #TODO implementare un controllo di errori
        return $errors;
    }

    public function loadDefault() {
        $this->load(true, 'default') ;
        return $this;
    }
    
    public function toOptionArray()
    {
        $ret = array();

        $coll = $this->getCollection()->load();
        foreach ($coll as $rec) {
            $row = array('value' => $rec->getId(), 'label' => $rec->getName(), 'extra' => '', 'country' => $rec->getCountry());
            if ($rec->getDefault()) {
                $row['extra'] = ' SELECTED ';
            }
            $ret[$rec->getId()] = $row;
        }

        return $ret;
    }

}

