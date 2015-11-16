<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Sitemap extends Mage_Core_Block_Template
{	
	/**
	 * Cache for the page collection
	 *
	 * @var Fishpig_AttributeSplashPro_Model_Resource_Page_Collection
	 */
	protected $_pages = null;
	
	/**
	 * Set the template
	 *
	 */
	public function _construct()
	{
		parent::_construct();
		
		$this->setTemplate('splash/sitemap.phtml');
	}
	
	/**
	 * Retrieve the doc type
	 *
	 * @return string
	 */
	public function getDocType()
	{
		return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}
	
	/**
	 * Retrieve the page collection
	 *
	 * @return Fishpig_AttributeSplashPro_Model_Resource_Page_Collection
	 */
	public function getPages()
	{
		if (is_null($this->_pages)) {
			$this->_pages = Mage::getResourceModel('splash/page_collection')
				->addStoreFilter(Mage::app()->getStore())
				->addFieldToFilter('status', Fishpig_AttributeSplashPro_Model_Page::STATUS_ENABLED)
				->load();
		}
		
		return $this->_pages;
	}
	
	/**
	 * Retrieve the change frequency
	 *
	 * @return string
	 */
	public function getChangeFrequency()
	{
		return Mage::getStoreConfig('splash/sitemap/change_frequency');
	}

	/**
	 * Retrieve the priority
	 *
	 * @return string
	 */
	public function getPriority()
	{
		return Mage::getStoreConfig('splash/sitemap/priority');
	}
	
	/**
	 * Retrieve the last modified date
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @return string
	 */
	public function getLastModifiedDate(Fishpig_AttributeSplashPro_Model_Page $page)
	{
		return ($date = $page->getUpdatedAt()) ? substr($date, 0, strpos($date, ' ')) : date('Y-m-d');
	}
}
