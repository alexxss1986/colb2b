<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_PriceFilters extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Prepare the form
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();

		$attributeOptions = array('price' => 'Price', 'final_price' => 'Special Price');;

		foreach($attributeOptions as $value => $label) {
			$fieldset = $form->addFieldset('splash_page_price_filter_' . $value, array(
				'legend'=> $this->__($label),
			));
			
			foreach(array('min' => 'Minimum Price', 'max' => 'Maximum Price') as $key => $label) {
				$fieldset->addField('price_filters_' . $value . '_' . $key, 'text', array(
					'name' => 'price_filters[' . $value . '][' . $key . ']',
					'title' => $this->helper('adminhtml')->__($label),
					'label' => $this->helper('adminhtml')->__($label),
				));
			}
		}

        $form->setHtmlIdPrefix('splash_');
        $form->setFieldNameSuffix('splash');
		$form->setValues($this->_getFormData());

		$this->setForm($form);
		
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
