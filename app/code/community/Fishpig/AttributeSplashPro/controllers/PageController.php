<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_PageController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Ensure the AMasty_Shopby module doesn't break Splash Pages
	 * This only runs when running a splash page, meaning Shopby module
	 * will still work
	 *
	 * @return $this
	 */
	public function preDispatch()
	{
		if (Mage::getConfig()->getNode('modules/Amasty_Shopby/active') == 'true') {
	    	Mage::getConfig()->setNode('modules/Amasty_Shopby/active', 'false', true);

	    	$toUnset = array(
	    		'global/blocks/amshopby',
	    		'global/blocks/catalog/rewrite/product_list_toolbar',
	    		'global/blocks/catalog/rewrite/layer_filter_attribute',
	    		'global/blocks/catalogsearch/rewrite/layer_filter_attribute',
	    		'global/events/controller_front_init_routers/observer/amshopby',
	    		'global/models/ambase',
	    		'global/models/amshopby',
	    		'global/models/amshopby_mysql4',
	    		'global/models/catalog/rewrite/layer_filter_price',
	    		'global/models/catalog/rewrite/layer_filter_attribute',
	    		'global/models/catalog/rewrite/layer_filter_category',
	    		'global/models/catalog/rewrite/layer_filter_item',
	    		'global/models/catalogsearch/rewrite/layer_filter_attribute',
	    	);
	    	
	    	foreach($toUnset as $block) {
	    		$base = dirname($block);
				$child = basename($block);
				
		    	$parent = Mage::getConfig()->getNode($base);

				if (isset($parent->$child)) {
					unset($parent->$child);
				}
		    }
		}
			
    	return parent::preDispatch();
	}

	/**
	 * Display the splash page
	 *
	 */
	public function viewAction()
	{
		if (($page = $this->_getPage()) !== false) {		
			Mage::register('current_layer', Mage::getSingleton('splash/layer'));

			$this->_initViewActionLayout($page);
			
			$this->renderLayout();
		}
		else {
			$this->_forward('noRoute');
		}
	}
	
	/**
	 * Initialise the view action layout for the page
	 * This includes setting META data, page template and other similar things
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @return $this
	 */
	protected function _initViewActionLayout(Fishpig_AttributeSplashPro_Model_Page $page)
	{
		$customHandles = array(
			'default',
			'splash_page_view_' . $page->getId(),
		);
		
		if ($template = $page->getTemplate()) {
			array_push($customHandles, 'page_' . $template, 'splash_page_view_' . strtoupper($template));
		}

		$this->_addCustomLayoutHandles($customHandles);

		$layout = $this->getLayout();

		if (($rootBlock = $layout->getBlock('root')) !== false) {
			$rootBlock->addBodyClass('splash-page-' . $page->getId());
		}
				
		if (($headBlock = $layout->getBlock('head')) !== false) {
			if ($page->getMetaDescription()) {
				$headBlock->setDescription($page->getMetaDescription());
			}
			
			if ($page->getMetaKeywords()) {
				$headBlock->setKeywords($page->getMetaKeywords());
			}
			
			if ($page->getRobots()) {
				$headBlock->setRobots($page->getRobots());
			}
			
			if (Mage::getStoreConfigFlag('splash/seo/use_canonical')) {
				$headBlock->addItem('link_rel', $page->getUrl(), 'rel="canonical"');
			}
			
			if (($title = $page->getPageTitle()) !== '') {
				$headBlock->setTitle($title);
			}
			else {
				$this->_title($page->getName());
			}
		}
		
		if (($breadBlock = $layout->getBlock('breadcrumbs')) !== false) {
			$breadBlock->addCrumb('home', array(
				'link' => Mage::getUrl(),
				'label' => $this->__('Home'),
				'title' => $this->__('Home'),				
			));
			
			if (($categories = $this->_getCategoriesForBreadcrumbs($page)) !== false) {
				foreach($categories as $category) {
					$breadBlock->addCrumb('splash_category_' . $category->getId(), array(
						'link' => $category->getUrl(),
						'label' => $category->getName(),
						'title' => $category->getName(),
					));
				}				
			}
			
			$breadBlock->addCrumb('splash_page', array(
				'label' => $page->getName(),
				'title' => $page->getName(),
			));
		}

		// Initialize the messages blocks
		$this->_initLayoutMessages('core/session');
		$this->_initLayoutMessages('checkout/session');
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');

		return $this;
	}
	
	/**
	 * Adds custom layout handles
	 *
	 * @param array $handles = array()
	 */
	protected function _addCustomLayoutHandles(array $handles = array())
	{
		$update = $this->getLayout()->getUpdate();
		
		foreach($handles as $handle) {
			$update->addHandle($handle);
		}
		
		$this->addActionLayoutHandles();
		$this->loadLayoutUpdates();
		
		$update->addUpdate($this->_getPage()->getLayoutUpdateXml());
		
		$this->generateLayoutXml();
		$this->generateLayoutBlocks();

		$this->_isLayoutLoaded = true;
		
		return $this;
	}

	/**
	 * Retrieve an array of categories for use in the breadcrumbs
	 * This only works if the splash page has  a single category filters
	 *
	 * @param Fishpig_AttributeSplashPro_Model_Page $page
	 * @return false|array
	 */
	protected function _getCategoriesForBreadcrumbs(FIshpig_AttributeSplashPro_Model_Page $page)
	{
		if ($categoryIds = $page->getCategoryIds()) {
			$collection = Mage::getResourceModel('catalog/category_collection')
				->addAttributeToSelect(array('name', 'url'))
				->addAttributeToFilter('entity_id', array('in' => $categoryIds))
				->addAttributeToFilter('is_active', 1)
				->addAttributeToFilter('level', array('gt' => 2))
				->setCurPage(1)
				->setPageSize(1)
				->load();
				
			if ($collection->count() === 1) {
				$category = $collection->getFirstItem();
				$categories = array($category);
				
				while($category->getLevel() > 2) {
					if (($category = $category->getParentCategory()) !== false) {
						array_unshift($categories, $category);
					}
				}
				
				return $categories;
			}
		}
		
		return false;
	}

	/**
	 * Retrieve the current page
	 * This should already have been set in the router so
	 * just retrieve it form the registry
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	protected function _getPage()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			return $page;
		}
		
		return false;
	}
}