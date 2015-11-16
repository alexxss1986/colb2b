<?php

class Webgriffe_Tntpro_Block_Adminhtml_Consignmentno extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_consignmentno';
        $this->_blockGroup = 'wgtntpro';
        $this->_headerText = Mage::helper('wgtntpro')->__('Gestisci');        
        parent::__construct();
        $this->_removeButton('add');
    }

}