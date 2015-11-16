<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_SitemapController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Display the splash page
	 *
	 */
	public function indexAction()
	{
		if (Mage::getStoreConfigFlag('splash/sitemap/enabled')) {
			$this->getResponse()
				->setHeader('Content-Type', 'text/xml; charset=utf-8', true)
				->setBody($this->getLayout()->createBlock('splash/sitemap')->toHtml());			
		}
		else {
			$this->_forward('noRoute');
		}
	}
}