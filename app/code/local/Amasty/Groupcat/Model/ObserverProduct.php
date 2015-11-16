<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */

class Amasty_Groupcat_Model_ObserverProduct
{

    const FORBIDDEN_ACTION_404      = '1';
    const FORBIDDEN_ACTION_REDIRECT = '2';


    /*
     *  hide products on category list
     */
    public function hideProducts(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('amgroupcat/general/disable') || Mage::registry('amgroupcat_fetching_category')) {
            return false;
        }

        Mage::register('amgroupcat_fetching_category', true, true);

        $productIds = array();
        $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('remove_product_links = 1'));

        if ($activeRules) {


            foreach ($activeRules as $rule) {

                // get directly restricted products
                $currentRuleProductIds = Mage::getModel('amgroupcat/product')->getCollection()
                                             ->addFieldToSelect('product_id')
                                             ->addFieldToFilter('rule_id', $rule['rule_id'])
                                             ->getData();

                foreach ($currentRuleProductIds as $productId) {
                    $productIds[] = $productId['product_id'];
                }

                // get all restricted products from restricted categories
                $catIds = array_merge(explode(',', trim($rule['categories'], ',')));
                if (!empty($catIds)) {
                    foreach ($catIds as $catId) {
                        if ($catId > 0) {
                            $products = Mage::getModel('catalog/category')->load($catId)
                                            ->getProductCollection()
                                            ->addAttributeToSelect('*')// add all attributes - optional
                                            ->addAttributeToFilter('status', 1);// enabled
                            foreach ($products as $product) {
                                $productIds[] = $product->getId();
                            }
                        }
                    }
                }
            }


            // add products to collection filter
            if (!empty($productIds)) {
                $productIds = array_unique($productIds);

                $observer->getCollection()
                         ->addFieldToFilter('entity_id', array(
                                 'nin' => $productIds,
                             )
                         );
            }

            // delete trigger
            Mage::unregister('amgroupcat_fetching_category');
        }

        return $this;
    }


     /*
     * direct product access by link
     */
    public function checkProductRestrictions(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('amgroupcat/general/disable')) {
            return false;
        }
        $action     = $observer->getEvent()->getData('controller_action')->getRequest()->getParams();
        $productId  = $action['id'];

        /*
         * check product restrictions
         */
        $activeRules = Mage::helper('amgroupcat')->getActiveRulesForProduct($productId);
        if ($activeRules){
            Mage::getModel('amgroupcat/observerCategory')->checkForbidRestrictions($activeRules);
        }

        /*
         * check category restrictions
         */
        $categoryId =  Mage::getModel('catalog/product')->load($productId)->getCategoryIds();
        if (is_array($categoryId) && isset($categoryId[0]) && $categoryId[0]>0){
            Mage::getModel('amgroupcat/observerCategory')->checkCategoryTreeRestrictions($categoryId[0]);
        }

        return $this;
    }

}