<?php
/**
 * ShopShark Blog Extension
 * @version   1.0 12.09.2013
 * @author    ShopShark http://www.shopshark.net <info@shopshark.net>
 * @copyright Copyright (C) 2010 - 2013 ShopShark
 */

class ShopShark_Blog_Block_Homeblog extends ShopShark_Blog_Block_Abstract
{
	
	/**
     * Initialize block's cache
     */
    protected function _construct()
    {
        parent::_construct();
			
		$this->addData(array(
            'cache_lifetime'    => 86400,
            'cache_tags'        => array(ShopShark_Blog_Model_Blog::CACHE_TAG)
        ));
		
		$this->setData('perPageOverride', 2);
    }
	
	/**
     * Get Key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
           ShopShark_Blog_Model_Blog::CACHE_TAG,
		   'HOMEBLOCK',
           Mage::app()->getStore()->getId()
        );
    }
	
    public function getPosts()
    {
		
        $collection = parent::_prepareCollection();

        $tag = $this->getRequest()->getParam('tag');
        if ($tag) {
            $collection->addTagFilter(urldecode($tag));
        }

        parent::_processCollection($collection);

        return $collection;
    }

}
