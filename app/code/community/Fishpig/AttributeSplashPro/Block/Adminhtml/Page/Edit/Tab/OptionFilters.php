<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_OptionFilters extends Mage_Adminhtml_Block_Widget_Form
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
		
		$attributes = Mage::getResourceModel('splash/page')->getSplashableAttributes();
		$operatorValues = $this->_getOperatorValues();
		
		foreach($attributes as $attribute) {
			$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attribute->getAttributeCode());

			$fieldset = $form->addFieldset('splash_page_filters_' . $attribute->getAttributeCode(), array(
				'legend'=> $attribute->getFrontendLabel(),
			));

			if ($attribute->usesSource()) {				
				$fieldType = $attribute->getFrontendInput() === 'boolean' ? 'select' : 'multiselect';

				$options = $attribute->getSource()->getAllOptions(false, true);
				
				if ($fieldType === 'select') {
					array_unshift($options, array('value' => '', 'label' => ''));
					
					$fieldType = 'asp_select';
					
					$fieldset->addType($fieldType, 'Fishpig_AttributeSplashPro_Lib_Varien_Data_Form_Element_Select');
				}
				
				$fieldset->addField('option_filters_' . $attribute->getAttributeCode() . '_value', $fieldType, array(
					'name' => 'option_filters[' . $attribute->getAttributeCode() . '][value]',
					'title' => $this->helper('adminhtml')->__('Options'),
					'label' => $this->helper('adminhtml')->__('Options'),
					'values' => $options,
				));
				
				if ($fieldType === 'multiselect') {
					$fieldset->addField('option_filters_' . $attribute->getAttributeCode() . '_operator', 'select', array(
						'name' => 'option_filters[' . $attribute->getAttributeCode() . '][operator]',
						'title' => $this->helper('adminhtml')->__('Operator'),
						'label' => $this->helper('adminhtml')->__('Operator'),
						'values' => $operatorValues,
					));
				}
				
				$fieldset->addField('option_filters_' . $attribute->getAttributeCode() . '_apply_to', 'select', array(
					'name' => 'option_filters[' . $attribute->getAttributeCode() . '][apply_to]',
					'title' => $this->helper('adminhtml')->__('Apply To'),
					'label' => $this->helper('adminhtml')->__('Apply To'),
					'values' => array(
						array('value' => '', 'label' => $this->__('Parent Products')),
						array('value' => 'simple', 'label' => $this->__('Associated Simple Products')),
					),
				));
				
				$fieldset->addField('option_filters_' . $attribute->getAttributeCode() . '_include_in_layered_nav', 'select', array(
					'name' => 'option_filters[' . $attribute->getAttributeCode() . '][include_in_layered_nav]',
					'title' => $this->helper('adminhtml')->__('Include in Layered Navigation'),
					'label' => $this->helper('adminhtml')->__('Include in Layered Navigation'),
					'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
				));
			}
		}

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
	
	/**
	 * Retrieve the operator values that can be used
	 *
	 * @return array
	 */
	protected function _getOperatorValues()
	{
		return array(
			array('value' => 'OR', 'label' => 'OR'),
			array('value' => 'AND', 'label' => 'AND'),
		);
	}
}
