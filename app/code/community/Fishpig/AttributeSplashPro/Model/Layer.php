<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Model_Layer extends Mage_Catalog_Model_Layer
{
	/**
	 * Retrieve the current category for use when generating product collections
	 * As we are using splash pages and not categories, this returns the splash page
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	public function getCurrentCategory()
	{
		if (!$this->hasCurrentCategory()) {
			$category = Mage::getModel('catalog/category')->load(
				Mage::app()->getStore()->getRootCategoryId()
			);

			$this->setData('current_category', $category);
		}
		
		return $this->_getData('current_category');
	}

	/**
	 * Retrieve the product collection for the Splash Page
	 *
	 * @return
	 */
	 public function getProductCollection()
	 {
		 $key = 'splashpro_' . $this->getPage()->getId();

		if (isset($this->_productCollections[$key])) {
			$collection = $this->_productCollections[$key];
		}
		else {
			$collection = $this->getPage()->getProductCollection();
			$this->prepareProductCollection($collection);
			$this->_productCollections[$key] = $collection;
		}

		return $collection;
	}
	
	/**
	 * Retrieve the splash page
	 * We add an array to children_categories so that it can act as a category
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
	 * Get the state key for caching
	 *
	 * @return string
	 */
	 public function getStateKey()
	 {
		if ($this->_stateKey === null) {
			$this->_stateKey = 'STORE_'.Mage::app()->getStore()->getId()
				. '_SPLASHPRO_' . $this->getPage()->getId()
				. '_CUSTGROUP_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
			}

		return $this->_stateKey;
	}

	/**
	 * Get default tags for current layer state
	 *
	 * @param   array $additionalTags
	 * @return  array
	*/
	public function getStateTags(array $additionalTags = array())
	{
		$additionalTags = array_merge($additionalTags, array(
			Mage_Catalog_Model_Category::CACHE_TAG . '_SPLASHPRO_' . $this->getPage()->getId()
		));
	
		return $additionalTags;
	}

	/**
	 * Stop the splash page attribute from dsplaying in the filter options
	 *
	 * @param   Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection $collection
	 * @return  Mage_Catalog_Model_Resource_Eav_Mysql4_Attribute_Collection
     */
	protected function _prepareAttributeCollection($collection)
	{
		parent::_prepareAttributeCollection($collection);
		
		if (($page = $this->getPage()) !== false) {
			$optionFilters = $page->getOptionFilters();

			if ($optionFilters && is_array($optionFilters)) {
				foreach($optionFilters as $attributeCode => $filter) {
					if (isset($filter['include_in_layered_nav']) && (int)$filter['include_in_layered_nav'] === 1) {
						unset($optionFilters[$attributeCode]);
					}
				}
				
				$attributeCodes = array_keys($optionFilters);
				
				if (count($attributeCodes) > 0) {
					$collection->addFieldToFilter('attribute_code', array('nin' => array_keys($optionFilters)));
				}
			}
		}

		return $collection;
	}
}
