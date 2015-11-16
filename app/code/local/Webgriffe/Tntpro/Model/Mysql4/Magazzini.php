<?php

class Webgriffe_Tntpro_Model_Mysql4_Magazzini extends Mage_Core_Model_Mysql4_Abstract
{
    #protected $_isPkAutoIncrement = false;

    protected function _construct()
    {
        $this->_init('wgtntpro/magazzini', 'magazzino_id');
    }

    public function resetDefaults()
    {
        $this->_getWriteAdapter()->query("UPDATE {$this->getMainTable()} tab SET tab.default=0");
    }

}

