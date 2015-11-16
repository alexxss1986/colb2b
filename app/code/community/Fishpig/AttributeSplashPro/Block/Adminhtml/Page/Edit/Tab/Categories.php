<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
	/**
	 * Retrieve currently edited splash page
	 *
	 * @return Fishpig_AttributeSplash_Model_Page
	*/
	public function getProduct()
	{
		return $this->getSplashPage();
	}

	/**
	 * Retrieve currently edited splash page
	 *
	 * @return Fishpig_AttributeSplash_Model_Page
	*/	
	public function getSplashPage()
	{
		return Mage::registry('splash_page');
	}
	
	/**
	 * Checks when this block is readonly
	 *
	 * @return boolean
	*/
	public function isReadonly()
	{
		return false;
	}
}