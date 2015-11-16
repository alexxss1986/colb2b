<?php

class Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Grid_Sino extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	
	function render(Varien_Object $row) {
		$value = $row->getData($this->getColumn()->getIndex());
		return !$value || (0==$value) ? Mage::helper('wgtntpro')->__('No') : Mage::helper('wgtntpro')->__('Sì');
	}
	
}
