<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Set the tab block options
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setId('splash_page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->__('Page Information'));
	}
	
	/**
	 * Add tabs to the tabs block
	 *
	 * @return $this
	 */
	protected function _beforeToHtml()
	{
		$layout = $this->getLayout();
		
		$this->addTab('page', array(
			'label' => $this->helper('adminhtml')->__('General'),
			'title' => $this->helper('adminhtml')->__('General'),
			'content' => $layout->createBlock('splash/adminhtml_page_edit_tab_page')->toHtml(),
		));
		
		$this->addTab('content', array(
			'label' => $this->helper('adminhtml')->__('Content'),
			'title' => $this->helper('adminhtml')->__('Content'),
			'content' => $layout->createBlock('splash/adminhtml_page_edit_tab_content')->toHtml(),
		));
		
		$this->addTab('meta', array(
			'label' => $this->helper('adminhtml')->__('Meta Data'),
			'title' => $this->helper('adminhtml')->__('Meta Data'),
			'content' => $layout->createBlock('splash/adminhtml_page_edit_tab_meta')->toHtml(),
		));

		if (Mage::registry('splash_page')) {
			$categoryFiltersHtml = $layout->createBlock('splash/adminhtml_page_edit_tab_categoryOperator')->toHtml() . $layout->createBlock('splash/adminhtml_page_edit_tab_categories')->toHtml();
	
			$categoryFiltersHtml = str_replace(Mage::helper('catalog')->__('Product Categories'), Mage::helper('catalog')->__('Category IDs'), $categoryFiltersHtml);
			
			$this->addTab('category_filters', array(
				'label' => $this->helper('adminhtml')->__('Category Filters'),
				'title' => $this->helper('adminhtml')->__('Category Filters'),
				'content' => $categoryFiltersHtml,
			));
		}
		
		$this->addTab('option_filters', array(
			'label' => $this->helper('adminhtml')->__('Option Filters'),
			'title' => $this->helper('adminhtml')->__('Option Filters'),
			'content' => $layout->createBlock('splash/adminhtml_page_edit_tab_optionFilters')->toHtml(),
		));
		
		$this->addTab('price_filters', array(
			'label' => $this->helper('adminhtml')->__('Price Filters'),
			'title' => $this->helper('adminhtml')->__('Price Filters'),
			'content' => $layout->createBlock('splash/adminhtml_page_edit_tab_priceFilters')->toHtml(),
		));

		return parent::_beforeToHtml();
	}
}
