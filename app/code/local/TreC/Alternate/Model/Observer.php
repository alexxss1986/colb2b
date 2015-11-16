<?php
class TreC_Alternate_Model_Observer
{
    public function alternateLinks()
    {
        $headBlock = Mage::app()->getLayout()->getBlock('head');

        $stores = Mage::app()->getStores();
        $prod = Mage::registry('current_product');
        $categ = Mage::registry('current_category');

        if($headBlock)
        {
            foreach ($stores as $store)
            {
                if($prod){
                    $categ ? $categId=$categ->getId() : $categId = null;
                    $url = $store->getBaseUrl() . Mage::helper('trec_alternate')->rewrittenProductUrl($prod->getId(), $categId, $store->getId());

                    //$storeCode = substr(Mage::getStoreConfig('general/locale/code', $store->getId()),0,2);
                    $storeCode=$store->getCode();
                    $headBlock->addLinkRel('alternate"' . ' hreflang="' . $storeCode, $url);
                }
                else if($categ){
                    $categId=$categ->getId();
                    $url = $store->getBaseUrl() . Mage::helper('trec_alternate')->rewrittenCategoryUrl($categId, $store->getId());

                    //$storeCode = substr(Mage::getStoreConfig('general/locale/code', $store->getId()),0,2);
                    $storeCode=$store->getCode();
                    $headBlock->addLinkRel('alternate"' . ' hreflang="' . $storeCode, $url);
                }


            }
        }
        return $this;
    }
}