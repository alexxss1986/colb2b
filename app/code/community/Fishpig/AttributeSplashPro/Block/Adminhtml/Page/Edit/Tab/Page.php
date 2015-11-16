<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_Page extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Prepare the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_page_general', array(
			'legend'=> $this->helper('adminhtml')->__('Page Information'),
		));
		
		$fieldset->addField('name', 'text', array(
			'name' => 'name',
			'label' => $this->helper('adminhtml')->__('Name'),
			'title' => $this->helper('adminhtml')->__('Name'),
			'required' => true,
			'class' => 'required-entry',
		));
		
		$fieldset->addField('url_key', 'text', array(
			'name' => 'url_key',
			'label' => $this->helper('adminhtml')->__('URL Key'),
			'title' => $this->helper('adminhtml')->__('URL Key'),
			'required' => true,
            'note' => Mage::helper('cms')->__('Relative to Website Base URL'),
			'class' => 'required-entry validate-identifier',
		));
		
		if (!Mage::app()->isSingleStoreMode()) {
			$field = $fieldset->addField('store_id', 'multiselect', array(
				'name' => 'stores[]',
				'label' => Mage::helper('cms')->__('Store View'),
				'title' => Mage::helper('cms')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
			));

			$renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
			
			if ($renderer) {
				$field->setRenderer($renderer);
			}
		}
		else {
			$fieldset->addField('store_id', 'hidden', array(
				'name' => 'stores[]',
				'value' => Mage::app()->getStore(true)->getId(),
			));
			
			if (($page = Mage::registry('splash_page')) !== null) {
				$page->setStoreId(Mage::app()->getStore(true)->getId());
			}
		}

		$fieldset->addField('status', 'select', array(
			'name' => 'status',
			'title' => $this->helper('adminhtml')->__('Status'),
			'label' => $this->helper('adminhtml')->__('Status'),
			'required' => true,
			'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
		));
		
		
		$fieldset = $form->addFieldset('splash_page_layout', array(
			'legend'=> $this->helper('adminhtml')->__('Display Settings'),
		));
		
		$fieldset->addField('layout_update_xml', 'editor', array(
			'name' => 'layout_update_xml',
			'label' => $this->helper('adminhtml')->__('Layout Update XML'),
			'title' => $this->helper('adminhtml')->__('Layout Update XML'),
			'style' => 'width:600px;',
		));
		
		$form->setValues($this->_getFormData());

		return parent::_prepareForm();
	}
	
	/**
	 * Retrieve the data used for the form
	 *
	 * @return array
	 */
	protected function _getFormData()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			return $page->getData();
		}

		return array(
			'status' => 1,
			'store_id' => Mage::app()->isSingleStoreMode() ? 0 : array(0),
		);
	}
}
