<?php

class Webgriffe_Tntpro_Block_Adminhtml_Magazzini extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_magazzini';
        $this->_blockGroup = 'wgtntpro';
        $this->_headerText = Mage::helper('wgtntpro')->__('Gestisci');
        parent::__construct();
    }
    
}