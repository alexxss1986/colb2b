<?php
class Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'wgtntpro';
		$this->_controller = 'adminhtml_magazzini';
		$this->_updateButton('save', 'label', Mage::helper('wgtntpro')->__('Salva'));
		$this->_updateButton('delete', 'label', Mage::helper('wgtntpro')->__('Cancella'));
	}
	
	public function getHeaderText()
	{
		if( Mage::registry('wgtntpro_data') && Mage::registry('wgtntpro_data')->getId()) 
		{
			return Mage::helper('wgtntpro')->__("Modifica Elemento");
		} 
		else 
		{
			return Mage::helper('wgtntpro')->__('Aggiungi Elemento');
		}
	}
	
}
