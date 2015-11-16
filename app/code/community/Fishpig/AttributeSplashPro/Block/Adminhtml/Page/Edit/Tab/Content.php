<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
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
		
		$fieldset = $form->addFieldset('splash_page_content', array(
			'legend'=> $this->helper('adminhtml')->__('Content'),
			'class' => 'fieldset-wide',
		));

		$htmlConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(array(
			'add_widgets' => false,
			'add_variables' => false,
			'add_image' => false,
			'files_browser_window_url' => $this->getUrl('adminhtml/cms_wysiwyg_images/index')
		));

		foreach(array( 'short_description' => 'Short Description', 'description' => 'Description') as $attribute => $label) {
			$fieldset->addField($attribute, 'editor', array(
				'name' => $attribute,
				'label' => $this->helper('adminhtml')->__($label),
				'title' => $this->helper('adminhtml')->__($label),
				'style' => 'width:100%; height:400px;',
				'config' => $htmlConfig
			));
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
		return ($page = Mage::registry('splash_page')) !== null ? $page->getData() : array();
	}
}
