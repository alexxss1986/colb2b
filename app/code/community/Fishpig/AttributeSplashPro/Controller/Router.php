<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
	/**
	 * Initialize Controller Router
	 *
	 * @param Varien_Event_Observer $observer
	*/
	public function initControllerRouters(Varien_Event_Observer $observer)
	{
		$observer->getEvent()->getFront()->addRouter('splash', $this);
	}

    /**
     * Validate and Match Cms Page and modify request
     *
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
    	$urlKey = trim($request->getPathInfo(), '/');
    	
    	if ($urlKey === 'splash-sitemap.xml') {
			$request->setModuleName($this->_getFrontName())
				->setControllerName('sitemap')
				->setActionName('index');

	    	return true;
    	}

    	$page = Mage::getModel('splash/page')
    		->setStoreId(Mage::app()->getStore()->getId())
    		->loadByUrlKey($urlKey);
		
		if (!$page->getId() || !$page->isEnabled()) {
			return false;
		}    	

		Mage::getConfig()->setNode('mageworx_seo/seosuite/disable_layered_rewrites', true, true);
		Mage::app()->getStore()->setConfig('mageworx_seo/seosuite/disable_layered_rewrites', true);
		
		Mage::register('splash_page', $page);
		
		$request->setModuleName($this->_getFrontName())
			->setControllerName('page')
			->setActionName('view')
			->setParam('page_id', $page->getId());
		
		$request->setAlias(
			Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
			$urlKey
		);

		return true;
	}
	
	/**
	 * Retrieve the frontName used by the module
	 *
	 * @return string
	 */
	protected function _getFrontName()
	{
		return (string)Mage::getConfig()->getNode()->frontend->routers->splash->args->frontName;
	}
}
