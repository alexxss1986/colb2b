<?php

class Webgriffe_Tntpro_Model_Mysql4_Consignmentno extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_isPkAutoIncrement = false;

    protected function _construct()
    {
        $this->_init('wgtntpro/consignmentno', 'consignmentno');
    }

    protected function _setResource($connections, $tables = null)
    {
        $this->_resources = Mage::getModel('core/resource'); #non uso il singleton per essere in una altra transaction del db mysql e salvare di sicuro il numero fornitomi da TNT

        if (is_array($connections)) {
            foreach ($connections as $k=>$v) {
                $this->_connections[$k] = $this->_resources->getConnection($v);
            }
        } else if (is_string($connections)) {
            $this->_resourcePrefix = $connections;
        }

        if (is_null($tables) && is_string($connections)) {
            $this->_resourceModel = $this->_resourcePrefix;
        } else if (is_array($tables)) {
            foreach ($tables as $k => $v) {
                $this->_tables[$k] = $this->_resources->getTableName($v);
            }
        } else if (is_string($tables)) {
            $this->_resourceModel = $tables;
        }
        return $this;
    }

}

