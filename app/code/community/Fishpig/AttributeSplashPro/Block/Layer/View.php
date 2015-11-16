<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Layer_View extends Mage_Catalog_Block_Layer_View
{
	/**
	 * Get layer object
	 *
	 * @return Mage_Catalog_Model_Layer
	*/
	public function getLayer()
	{
		return Mage::getSingleton('splash/layer');
	}
}
