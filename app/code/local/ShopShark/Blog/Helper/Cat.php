<?php
/**
 * ShopShark Blog Extension
 * @version   1.0 12.09.2013
 * @author    ShopShark http://www.shopshark.net <info@shopshark.net>
 * @copyright Copyright (C) 2010 - 2013 ShopShark
 */

class ShopShark_Blog_Helper_Cat extends Mage_Core_Helper_Abstract {

    /**
     * Renders CMS page
     *
     * Call from controller action
     *
     * @param Mage_Core_Controller_Front_Action $action
     * @param integer $pageId
     * @return boolean
     */
    public function renderPage(Mage_Core_Controller_Front_Action $action, $identifier=null) {
        if (!$cat_id = Mage::getSingleton('blog/cat')->load($identifier)->getcatId()) {
            return false;
        }

        $page_title = Mage::getSingleton('blog/cat')->load($identifier)->getTitle();
        $blog_title = Mage::getStoreConfig('blog/blog/title') . " - ";

        $action->loadLayout();
        if ($storage = Mage::getSingleton('customer/session')) {
            $action->getLayout()->getMessagesBlock()->addMessages($storage->getMessages(true));
        }
        $action->getLayout()->getBlock('head')->setTitle($page_title);
        
		$_pinterest = Mage::getStoreConfig('blog/blog/pinterest') == 1;
		
		/*
          if (Mage::getStoreConfig('blog/rss/enable'))
          {
          Mage::helper('blog')->addRss($action->getLayout()->getBlock('head'), Mage::getUrl(Mage::getStoreConfig('blog/blog/route') . "/cat/" .$identifier) . "rss");
          }
         */
        
		$action->getLayout()->getBlock('root')->setTemplate($_pinterest ? 'page/1column.phtml' : Mage::getStoreConfig('blog/blog/layout'));
        $action->renderLayout();

        return true;
    }

}
