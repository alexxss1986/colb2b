<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */

class Amasty_Groupcat_Model_ObserverCategory
{

    const FORBIDDEN_ACTION_404      = '1';
    const FORBIDDEN_ACTION_REDIRECT = '2';


    /*
     * hide category links from top menu block
     */
    public function topMenuCategoryLinksHide(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('amgroupcat/general/disable')) {
            return false;
        }

        $categoryIds = array();
        $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('remove_category_links = 1'));/* active rules which have "remove_category_links" flag */
        if(!empty($activeRules)) {
            foreach($activeRules as $rule) {
                $ids = explode(',',$rule['categories']);
                $categoryIds = array_merge($ids,$categoryIds);
            }

            $categoryIds = array_unique($categoryIds);
            $menu = $observer->getEvent()->getMenu();

            $this->hideMenuItems($menu, $categoryIds);
        }

        return $this;
    }

    private function hideMenuItems($menu, $categoryIds)
    {
        $menuCollection = $menu->getChildren();
        foreach ($menuCollection as $menuItem) {
            if(in_array(substr($menuItem->getId(), 14), $categoryIds)) {
                $menuCollection->delete($menuItem);
            } elseif($menuItem->hasChildren()) {
                $this->hideMenuItems($menuItem, $categoryIds);
            }
        }
    }



    /*
     * get ALL restrictions for current category, including all parent categories restrictions
     */
    public function checkCategoryRestrictions(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('amgroupcat/general/disable')) {
           return false;
        }

        $action = $observer->getEvent()->getData('controller_action')->getRequest()->getParams();
        $categoryId = $action['id'];

        /* recursive walk and check restrictions */
        $this->checkCategoryTreeRestrictions($categoryId);

        return $this;
    }


    /*
     * get restriction rules by recursive walk
     * from child to top parent (root) item
     */
    public function checkCategoryTreeRestrictions($categoryId)
    {
        /*
         * get restriction rules for category
         * and check current category access restriction
         * @TODO-LATER: recursive `LIKE %x%` selects - need to think about other ways to implement feature
         */
        $activeRules = Mage::helper('amgroupcat')->getActiveRules(array("categories LIKE '%,$categoryId,%'"));
        if(!empty($activeRules)) {
                $this->checkForbidRestrictions($activeRules);
        }

        /*
         * recursively check all parent categories for any restrictions
         */
        $currentCategory = Mage::getModel('catalog/category')->load($categoryId);
        $path = $currentCategory->getPath();  //path: 1/2/10/12
        $ids = explode('/', $path);
        if(isset($ids[1])) {
            $topId = $ids[1];
            if($categoryId != $topId) {
                $categoryId = Mage::getModel('catalog/category')->load($categoryId)->getParentCategory()->getId();
                $this->checkCategoryTreeRestrictions($categoryId);
            }
        }

        return true;
    }
    
    public function checkForbidRestrictions($rules)
    {
        if (!is_array($rules) && count($rules)<1){
            return false;
        }

        foreach($rules as $rule){
            if(!empty($rule)) {
                if(!$rule['forbidden_action']){
                   return false;
                }

                if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                } else {
                    $groupId = 0;
                }

                $pos = strpos($rule['cust_groups'],','.$groupId.',');

                /*
                 * only if "remove_category_links" and "allow_direct_links" are selected - restrict direct access to category
                 */
                if($pos!==false && !$rule['allow_direct_links']) {
                    $allCmsPages = Mage::helper('amgroupcat')->getCmsPages();
                    $url = 'no-route';
                    if ($rule['forbidden_action'] == self::FORBIDDEN_ACTION_REDIRECT) {
                        $ruleCmsPage = $rule['cms_page'];
                        $url = Mage::getModel('cms/page')->getCollection()
                                          ->addFieldToFilter('title', $allCmsPages[$ruleCmsPage])
                                          ->getFirstItem()
                                          ->getData('identifier')
                        ;
                    } else if ($rule['forbidden_action'] == self::FORBIDDEN_ACTION_404){
                        $url = '404';
                    }

                    $url = Mage::getBaseUrl() . $url;
                    Mage::app()->getFrontController()->getResponse()->setRedirect($url);
                }
            }
        }
    }



    /*
     * hide categories from any navigation blocks
     */
    public function hideCategoriesFromNavigation(Varien_Event_Observer $observer)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            $categoryIds = array();
            $collection = $observer->getEvent()->getCategoryCollection();
            $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('remove_category_links = 1'));

            if (!empty($activeRules)) {
                foreach ($activeRules as $rule) {
                    $currentRuleCategoryIds = explode(',',trim($rule['categories'],','));
                    $categoryIds = array_merge($categoryIds,$currentRuleCategoryIds);
                }
                $categoryIds = array_unique($categoryIds);
            }

            if (!empty($categoryIds)) {
                $collection->addFieldToFilter('entity_id', array('nin' => $categoryIds));
            }

            return $this;
        }

    }
    
}