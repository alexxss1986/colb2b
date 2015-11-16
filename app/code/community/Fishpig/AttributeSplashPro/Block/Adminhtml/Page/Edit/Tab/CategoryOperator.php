<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_CategoryOperator extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Prepare the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
        
		$this->setForm($form);
		
		$fieldset = $form->addFieldset('splash_page_coperator', array(
			'legend'=> $this->helper('adminhtml')->__('Category Operator'),
			'class' => 'fieldset-wide',
		));

		$fieldset->addField('category_operator', 'select', array(
			'name' => 'category_operator',
			'label' => Mage::helper('cms')->__('Operator'),
			'title' => Mage::helper('cms')->__('Operator'),
			'values' => array(
				array('value' => 'OR', 'label' => 'OR'),
				array('value' => 'AND', 'label' => 'AND'),
			)
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
		return ($page = Mage::registry('splash_page')) !== null 
			? $page->getAdminFilterData() 
			: array();
	}
}
