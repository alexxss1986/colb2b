<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

// This call adds support for Magento 1.5.1.0 and earlier
Mage::helper('splash');
 
class Fishpig_AttributeSplashPro_Model_Resource_Page extends Mage_Core_Model_Resource_Db_Abstract
{
	/**
	 * The default operator used for multiselect attributes
	 *
	 * @var const string
	 */
	const FILTER_OPERATOR_DEFAULT = 'AND';

	/**
	 * Fields to be serialized before saving
	 * This applies to the filter fields
	 *
	 * @var array
	 */
     protected $_serializableFields = array(
     	'category_filters' => array('a:0:{}', array()),
     	'option_filters' => array('a:0:{}', array()),
     	'price_filters' => array('a:0:{}', array()),
     );
    
	/**
	 * Default attribute codes which can't be used for splash pages
	 *
	 * @var array
	 */
	protected $_nonSplashableAttributes = array(
		'country_of_manufacture',
		'custom_design',
		'enable_googlecheckout',
		'gift_message_available',
		'is_recurring',
		'msrp_enabled',
		'msrp_display_actual_price_type',
		'options_container',
		'page_layout',
		'price_view',
		'status',
		'visibility',
	);
	
	/**
	 * An array of fields that can be used for price filters
	 * These aren't attributes but MySQL query column names
	 *
	 * @var array
	 */
	protected $_splashablePriceFields = array(
		'price' => 'Price', 
		'final_price' => 'Final Price',
	);
	
	/**
	 * Init the entity type
	 *
	 */
	public function _construct()
	{
		$this->_init('splash/page', 'page_id');
	}
	
	/**
	 * Retrieve select object for load object data
	 * This gets the default select, plus the attribute id and code
	 *
	 * @param   string $field
	 * @param   mixed $value
	 * @return  Zend_Db_Select
	*/
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = $this->_getReadAdapter()->select()
			->from(array('main_table' => $this->getMainTable()))
			->where("`main_table`.`{$field}` = ?", $value)
			->limit(1);
			
		$adminId = Mage_Core_Model_App::ADMIN_STORE_ID;
		
		$storeId = $object->getStoreId();
		
		if ($storeId !== $adminId) {
			$select->join(
				array('store' => $this->getTable('splash/page_store')),
				$this->_getReadAdapter()->quoteInto('`store`.`page_id` = `main_table`.`page_id` AND `store`.`store_id` IN (?)', array($adminId, $storeId)),
				''
			);
			
			$select->order('store.store_id DESC');
		}
		
		return $select;
	}
	
	/**
	 * Retrieve a collection of products created by the page
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 */
	public function getProductCollection(Fishpig_AttributeSplashPro_Model_Page $page)
	{
		$storeId = ((int)$page->getStoreId() === 0)
			? Mage::app()->getStore()->getId()
			: $page->getStoreId();

		$products = Mage::getResourceModel('catalog/product_collection')
			->setStoreId($storeId);

		if (is_array($optionFilters = $page->getOptionFilters())) {
			foreach($optionFilters as $attribute => $data) {
				$values = (array)$data['value'];
				$operator = isset($data['operator']) ? $data['operator'] : self::FILTER_OPERATOR_DEFAULT;
				$attributeModel = $products->getResource()->getAttribute($attribute);

				if (isset($data['apply_to']) && $data['apply_to'] === 'simple') {
					if ($operator === 'OR' && count($values) > 1) {
						$this->_addEavAttributeOptionFilterToProductCollection($products, $attributeModel, $values, $page, $cond = 'IN (?)');
					}
					else {
						foreach($values as $value) {
							$this->_addEavAttributeOptionFilterToProductCollection($products, $attributeModel, $value, $page);
						}
					}
				}
				else if ($attributeModel->getFrontendInput() === 'multiselect') {
					if ($operator === 'OR') {
						$cond = array();
						
						foreach($values as $value) {
							$cond[] = array('finset' => array($value));
						}

						$products->addAttributeToFilter($attribute, $cond);
					}
					else {
						foreach($values as $value) {
							$products->addAttributeToFilter($attribute, array('finset' => $value));
						}
					}
				}
				else if ($operator === 'OR' && count($values) > 1) {
						$products->addAttributeToFilter($attribute, array('in' => $values));
				}
				else {
					foreach($values as $value) {
						$products->addAttributeToFilter($attribute, $value);
					}
				}
			}
		}

		if (is_array($categoryIds = $page->getCategoryIds())) {
			if ($page->getCategoryOperator() === 'AND' || count($categoryIds) === 1) {
				foreach($categoryIds as $categoryId) {
					$products->joinField('category_id_' . $categoryId,
						'catalog/category_product',
						'category_id',
						'product_id=entity_id',
						'at_category_id_' . $categoryId . '.category_id=' . (int)$categoryId
					);
				}
			}
			else {
				$read = Mage::getSingleton('core/resource')->getConnection('core_read');
				$cond = $read->quoteInto('`splash_category`.`product_id` = `e`.`entity_id` AND `splash_category`.`category_id` IN (?)', $categoryIds);

				$products->getSelect()->distinct()
					->join(array('splash_category' => $this->getTable('catalog/category_product')),
						$cond,
						''
					);
			}
		}

		if (is_array($priceFilters = $page->getPriceFilters())) {
			$splashableFields = $this->getSplashablePriceFields();
			
			foreach($priceFilters as $attribute => $data) {
				if (!isset($splashableFields[$attribute])) {
					continue;
				}
				
				if ($attribute === 'final_price') {
					$products->getSelect()->where('price_index.price <> price_index.final_price');
				}

				if ($data['min']) {
					$products->getSelect()->where('price_index.' . $attribute . ' >= ?',  $data['min']);
				}
				
				if ($data['max']) {
					$products->getSelect()->where('price_index.' . $attribute . ' <= ?',  $data['max']);
				}
			}
		}

		return $products;	
	}

	/**
	 * Filter the collection by an EAV attribute option
	 *
	 * @param $products
	 * @param Mage_Eav_Model_Entity_Attribute $attribute
	 * @param int|array $value
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @param string $cond
	 * @return $this
	 */
	protected function _addEavAttributeOptionFilterToProductCollection($products, $attribute, $value, $page, $cond = '= ?')
	{
		$alias = $attribute->getAttributeCode() . '_index';
		
		if (!is_array($value)) {
			$alias .= '_' . $value;	
		}

		$products->getSelect()
			->distinct()
			->join(
				array($alias => $this->getTable('catalog/product_index_eav')),
				"`{$alias}`.`entity_id` = `e`.`entity_id`"
				. $this->_getReadAdapter()->quoteInto(" AND `{$alias}`.`attribute_id` = ? ", $attribute->getAttributeId())
				. $this->_getReadAdapter()->quoteInto(" AND `{$alias}`.`store_id` = ? ", $page->getStoreId())
				. $this->_getReadAdapter()->quoteInto(" AND `{$alias}`.`value` " . $cond, $value),
				''
			);

		return $this;
	}

	/**
	 * Set the option filter array and clean it
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @param array $filters
	 * @return $this
	 */
	public function setCategoryFilters(Fishpig_AttributeSplashPro_Model_Page $page, $filters)
	{
		$page->setData('category_filters', $this->_cleanCategoryFilters($filters));
		
		return $this;
	}
	
	/**
	 * Clean the category filter data
	 *
	 * @param array $filters
	 * @return array
	 */
	protected function _cleanCategoryFilters(array $filters)
	{
		if (!isset($filters['ids']) || !is_array($filters['ids'])) {
			$filters['ids'] = array();
		}
		
		$filters['ids'] = array_unique($filters['ids']);
		
		if (!isset($filters['operator']) || empty($filters['operator'])) {
			$fitlers['operator'] = self::FILTER_OPERATOR_DEFAULT;
		}

		return $filters;
	}

	/**
	 * Set the option filter array and clean it
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @param array $filters
	 * @return $this
	 */
	public function setOptionFilters(Fishpig_AttributeSplashPro_Model_Page $page, $filters)
	{
		$page->setData('option_filters', $this->_cleanOptionFilters($filters));
		
		return $this;
	}
	
	/**
	 * Clean the filter array before saving and after loading
	 *
	 * @param array $filters
	 * @return array
	 */
	protected function _cleanOptionFilters(array $filters)
	{
		foreach($filters as $attribute => $data) {
			if (!isset($data['value'])) {
				unset($filters[$attribute]);
				continue;
			}	
			
			if (is_array($data['value'])) {
				foreach($data['value'] as $key => $value) {
					if (trim($value) === '') {
						unset($data['value'][$key]);
					}
				}
				
				if (count($data['value']) === 0) {
					unset($filters[$attribute]);
					continue;
				}
			
				if (!isset($data['operator'])) {
					$filters[$attribute]['operator'] = self::FILTER_OPERATOR_DEFAULT;
				}
				
				$filters[$attribute]['apply_to'] = isset($data['apply_to'])
					? $data['apply_to']
					: '';
					
				$filters[$attribute]['include_in_layered_nav'] = isset($data['include_in_layered_nav'])
					? (int)$data['include_in_layered_nav']
					: 0;
			}
			else if (trim($data['value']) === '') {
				unset($filters[$attribute]);
				continue;
			}
		}

		return $filters;
	}

	/**
	 * Retrieve the array of price fields that can be used in the filter
	 *
	 * @return array
	 */
	public function getSplashablePriceFields()
	{
		return $this->_splashablePriceFields;
	}
	
	/**
	 * Set the price filter array and clean it
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @param array $filters
	 * @return $this
	 */
	public function setPriceFilters(Fishpig_AttributeSplashPro_Model_Page $page, $filters)
	{
		$page->setData('price_filters', $this->_cleanPriceFilters($filters));
		
		return $this;
	}

	/**
	 * Clean the filter array before saving and after loading
	 *
	 * @param array $filters
	 * @return array
	 */
	protected function _cleanPriceFilters(array $filters)
	{
		foreach($filters as $attribute => $filter) {
			if (!isset($filter['min']) || !isset($filter['max'])) {
				unset($filters[$key]);
				continue;
			}
			
			list($min, $max) = array_values($filter);

			if (!$min) {
				$min = null;
			}
			
			if (!$max) {
				$max = null;
			}
			
			if (is_null($min) && is_null($max)) {
				unset($filters[$attribute]);
				continue;
			}
			
			$filters[$attribute] = array(
				'min' => $min,
				'max' => $max,
			);
		}

		return $filters;
	}
		
	/**
	 * Convert the filter data into a format used to pre-fill Admin forms
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @return array
	 */
	protected function _loadAdminData(Fishpig_AttributeSplashPro_Model_Page $page)
	{
		$adminFilterData = array();
		
		if (is_array($options = $page->getOptionFilters())) {
			foreach($options as $key => $data) {
				if (is_array($data) && isset($data['value']) && $data['value'] !== '') {
					$adminFilterData['option_filters_' . $key . '_value'] = $data['value'];
					$adminFilterData['option_filters_' . $key . '_operator'] = isset($data['operator']) && $data['operator'] ? $data['operator'] : 'AND';
					$adminFilterData['option_filters_' . $key . '_apply_to'] = isset($data['apply_to']) && $data['apply_to'] ? $data['apply_to'] : '';
					$adminFilterData['option_filters_' . $key . '_include_in_layered_nav'] = isset($data['include_in_layered_nav']) && $data['include_in_layered_nav'] ? (int)$data['include_in_layered_nav'] : 0;
				}
			}
		}

		if (is_array($prices = $page->getPriceFilters())) {
			foreach($prices as $attribute => $price) {
				$adminFilterData['price_filters_' . $attribute . '_attribute'] = $attribute;
				$adminFilterData['price_filters_' . $attribute . '_min'] = $price['min'];
				$adminFilterData['price_filters_' . $attribute . '_max'] = $price['max'];
			}
		}

		if ($operator = $page->getCategoryOperator()) {
			$adminFilterData['category_operator'] = $operator;
		}

		$page->setAdminFilterData($adminFilterData);
	}
		
	/**
	 * Function called after a model is loaded (but not when a collection of models are loaded)
	 * If filters set, unserialize (convert to an array)
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */
	protected function _afterLoad(Mage_Core_Model_Abstract $object)
	{
		if ($object->getId()) {
			$storeIds = $this->lookupStoreIds($object->getId());
			
			
			if ($this->isAdmin()) {
				$object->setData('store_id', $storeIds);
			}
			else {
				$object->setStoreId(Mage::app()->getStore(true)->getId());
				
				if (count($storeIds) === 1) {
					if ($storeIds[0] != 0) {
						$object->setStoreId((int)array_shift($storeIds));
					}
				}
			}
		}
		
		if ($this->isAdmin()) {
			$this->_loadAdminData($object);
		}
		
		return parent::_afterLoad($object);
	}

	/**
	 * Function called before a model is saved
	 * Serializes the filters array (if set) and performs other data checks
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */
	protected function _beforeSave(Mage_Core_Model_Abstract $object)
	{
		if ($object->isObjectNew()) {
			$object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
		}

		$object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

		return parent::_beforeSave($object);
	}

	/**
	 * Function called after a model is saved
	 * Save store associations
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return $this
	 */
	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{
		if ($object->getId()) {
			$oldStores = $this->lookupStoreIds($object->getId());
			$newStores = (array)$object->getStores();
	
			if (empty($newStores)) {
				$newStores = (array)$object->getStoreId();
			}
	
			$table  = $this->getTable('splash/page_store');
			$insert = array_diff($newStores, $oldStores);
			$delete = array_diff($oldStores, $newStores);
			
			if ($delete) {
				$this->_getWriteAdapter()->delete($table, array('page_id = ?' => (int) $object->getId(), 'store_id IN (?)' => $delete));
			}
			
			if ($insert) {
				$data = array();
			
				foreach ($insert as $storeId) {
					$data[] = array(
						'page_id'  => (int) $object->getId(),
						'store_id' => (int) $storeId
					);
				}

				$this->_getWriteAdapter()->insertMultiple($table, $data);
			}
		}
		
		// Ensure that attribute's used are set to be used in the product listing
		if (is_array($optionFilters = $object->getOptionFilters())) {
			foreach($optionFilters as $attribute => $data) {
				$attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', $attribute);
				
				if ($attribute->getId() && !$attribute->getUsedInProductListing()) {
					try {
						$attribute->setUsedInProductListing(1)->save();
					}
					catch (Exception $e) {
						Mage::logException($e);
					}
				}
			}
		}
		
		return parent::_afterSave($object);
	}
	
	/**
	 * Retrieve all attributes that can be used as option
	 * filters for splash pages
	 *
	 * @return
	 */
	public function getSplashableAttributes()
	{
		$productEntityTypeId = Mage::getResourceModel('catalog/product')->getTypeId();
		
		$collection = Mage::getResourceModel('eav/entity_attribute_collection')
			->setEntityTypeFilter($productEntityTypeId)
			->addFieldToFilter('attribute_code', array('nin' => $this->_nonSplashableAttributes))
			->addFieldToFilter('frontend_input', array('in' => array('select', 'multiselect', 'boolean')))
			->load();
		
		return $collection;
	}
	
	/**
	 * Get store ids to which specified item is assigned
	 *
	 * @param int $id
	 * @return array
	*/
	public function lookupStoreIds($pageId)
	{
		$select = $this->_getReadAdapter()->select()
			->from($this->getTable('splash/page_store'), 'store_id')
			->where('page_id = ?', (int)$pageId);
	
		return $this->_getReadAdapter()->fetchCol($select);
	}
	
	/**
	 * Determine whether the current store is the Admin store
	 *
	 * @return bool
	 */
	public function isAdmin()
	{
		return (int)Mage::app()->getStore()->getId() === Mage_Core_Model_App::ADMIN_STORE_ID;
	}
}
