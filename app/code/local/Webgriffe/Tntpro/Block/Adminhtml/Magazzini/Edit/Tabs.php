<?php

class Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('wgtntpro_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('wgtntpro')->__('Menu Item'));
	}

	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
			'label' => Mage::helper('wgtntpro')->__('Magazzino'),
			'title' => Mage::helper('wgtntpro')->__('Magazzino'),
			'content' => $this->getLayout()->createBlock('wgtntpro/adminhtml_magazzini_edit_tab_form')->toHtml(),
		));

		return parent::_beforeToHtml();
	}

}