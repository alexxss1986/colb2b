<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Prepare the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('splash_')
        	->setFieldNameSuffix('splash');
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_page_meta', array(
			'legend'=> $this->helper('adminhtml')->__('Meta Data'),
			'class' => 'fieldset-wide',
		));

		$fieldset->addField('page_title', 'text', array(
			'name' => 'page_title',
			'label' => Mage::helper('cms')->__('Page Title'),
			'title' => Mage::helper('cms')->__('Page Title'),
		));
		
		$fieldset->addField('meta_description', 'textarea', array(
			'name' => 'meta_description',
			'label' => Mage::helper('cms')->__('Description'),
			'title' => Mage::helper('cms')->__('Meta Description'),
		));
		
		$fieldset->addField('meta_keywords', 'textarea', array(
			'name' => 'meta_keywords',
			'label' => Mage::helper('cms')->__('Keywords'),
			'title' => Mage::helper('cms')->__('Meta Keywords'),
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
		return ($page = Mage::registry('splash_page')) !== null ? $page->getData() : array();
	}
}
