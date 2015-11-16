<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Model_Page extends Mage_Core_Model_Abstract
{
	/**
	 * Status flags for splash page
	 *
	 * @var const int
	 */
	const STATUS_ENABLED = 1;
	const STATUS_DISABLED = 0;
	
	/**
	 * Product collection
	 *
	 * @var 
	 */
	protected $_productCollection = null;
		
	/**
	 * Init the entity type
	 *
	 */
	public function _construct()
	{
		$this->_init('splash/page');
	}
	
	/**
	 * Load a splash page by it's URL key
	 * The urlSuffixi is stripped from the end of the url key
	 *
	 * @param string $urlKey
	 * @return $this
	 */
	public function loadByUrlKey($urlKey)
	{
		if ($urlSuffix = trim($this->_getUrlSuffix(), '/')) {
			if (substr($urlKey, -(strlen($urlSuffix))) === $urlSuffix) {
				$urlKey = substr($urlKey, 0, -(strlen($urlSuffix)));
			}
			else {
				return $this->setData(null)->setId(null);
			}
		}
		
		return $this->load($urlKey, 'url_key');
	}
	
	/**
	 * Retrieve the store ID of the splash page
	 * This isn't always the only store it's associated with
	 * but the current store ID
	 *
	 * @return int
	 */
	public function getStoreId()
	{
		if (!$this->hasStoreId()) {
			$this->setStoreId((int)Mage::app()->getStore(true)->getId());
		}
		
		return (int)$this->_getData('store_id');
	}

	/**
	 * Determine whether the page is enabled
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		return (int)$this->getStatus() === self::STATUS_ENABLED;
	}
	
	/**
	 * Retrieve the URL of the page
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return Mage::getUrl('', array()) . $this->getUrlKey() . $this->_getUrlSuffix();
	}
	
	/**
	 * Retrieve a collection of products created by the page
	 *
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 */
	public function getProductCollection()
	{
		if (is_null($this->_productCollection)) {
			$this->_productCollection = $this->getResource()->getProductCollection($this);
		}
		
		return $this->_productCollection;
	}

	/**
	 * Retrieve the URL suffix
	 *
	 * @return string
	 */
	protected function _getUrlSuffix()
	{
		return trim(Mage::getStoreConfig('splash/seo/url_suffix'));
	}
	
	/**
	 * Retrieve the template used for this splash page
	 * This will be used to add an XML layout handle to change the root
	 * page template
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		if (($template = trim($this->_getData('template'))) !== '') {
			return $template;
		}
		else if (($template = trim(Mage::getStoreConfig('splash/page/template'))) !== '') {
			return $template;
		}
		return 'two_columns_left';
	}

	/**
	 * Set filter data
	 * This is mostly called in the Admin
	 *
	 * @param array|string $filters
	 * @return mixed
	 */
	public function setCategoryFilters($filters)
	{
		$this->getResource()->setCategoryFilters($this, $filters);
		
		return $this;
	}

	/**
	 * Retrieve the category filters
	 *
	 * @return false|array
	 */
	public function getCategoryFilters()
	{
		return $this->_getFilter('category_filters');
	}
	
	/**
	 * Retrieve an array of category IDS
	 *
	 * @return array
	 */
	public function getCategoryIds()
	{
		if (($filter = $this->getCategoryFilters()) !== false) {
			if (isset($filter['ids']) && is_array($filter['ids'])) {
				return array_values($filter['ids']);
			}
		}
		
		return array();
	}
	
	/**
	 * Retrieve the category operator
	 *
	 * @return array
	 */
	public function getCategoryOperator()
	{
		if (($filter = $this->getCategoryFilters()) !== false) {
			if (isset($filter['operator'])) {
				return $filter['operator'];
			}
		}
		
		return Fishpig_AttributeSplashPro_Model_Resource_Page::FILTER_OPERATOR_DEFAULT;
	}
	
	/**
	 * Set filter data
	 * This is mostly called in the Admin
	 *
	 * @param array|string $filters
	 * @return mixed
	 */
	public function setOptionFilters($filters)
	{
		$this->getResource()->setOptionFilters($this, $filters);
		
		return $this;
	}
	
	/**
	 * Set filter data
	 * This is mostly called in the Admin
	 *
	 * @param array|string $filters
	 * @return mixed
	 */
	public function setPriceFilters($filters)
	{
		$this->getResource()->setPriceFilters($this, $filters);
		
		return $this;
	}
	
	/**
	 * Retrieve the price filters
	 *
	 * @return false|array
	 */
	public function getPriceFilters()
	{
		return $this->_getFilter('price_filters');
	}
	
	/**
	 * Retrieve a filter
	 *
	 * @param string $key
	 * @return false|array
	 */
	protected function _getFilter($key)
	{
		$filters = $this->getData($key);
		
		if (!$filters || (is_array($filters) && count($filters) === 0)) {
			return false;
		}
		
		return $filters;
	}
	
	/**
	 * Clean the internal filter arrays
	 *
	 * @return $this
	 */
	protected function _cleanFilters()
	{
		if ($filters = $this->getOptionFilters()) {
			$this->setOptionFilters($filters);
		}
		
		if ($filters = $this->getPriceFilters()) {
			$this->setPriceFilters($filters);
		}
		
		return $this;
	}
	
	/**
	 * Cleans filter data before saving it
	 *
	 * @return $this
	 */
	protected function _beforeSave()
	{
		$this->_cleanFilters();
		
		return parent::_beforeSave();
	}
	
	/**
	 * Remove the WHERE portion of the SQL query
	 *
	 * @param Varien_Event_Observer $observer
	 * @return $this
	 */
	public function catalogPreparePriceSelectObserver(Varien_Event_Observer $observer)
	{
		if ($observer->getEvent()) {
	        $observer->getEvent()
	        	->getSelect()
	        	->reset(Zend_Db_Select::WHERE);
		}

        return $this;
	}
	
	/**
	 * Retrieve the short description
	 *
	 * @return string
	 */
	public function getShortDescription()
	{
		return Mage::helper('cms')->getBlockTemplateProcessor()->filter($this->_getData('short_description'));
	}

	/**
	 * Retrieve the description
	 *
	 * @return string
	 */	
	public function getDescription()
	{
		return Mage::helper('cms')->getBlockTemplateProcessor()->filter($this->_getData('description'));
	}
}