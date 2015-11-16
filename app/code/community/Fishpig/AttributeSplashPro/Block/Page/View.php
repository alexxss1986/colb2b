<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Page_View extends Mage_Core_Block_Template
{	
	/**
	 * Retrieve the current page
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	public function getPage()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			return $page;
		}
		
		return false;
	}
	
	/**
	 * Retrieve the HTML of the product list
	 *
	 * @return string
	 */
	public function getProductListHtml()
	{
		return $this->getChildHtml('product_list');
	}
}
