<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Helper_Data extends Mage_Core_Helper_Abstract
{

    /*
     * get list of customer groups for adminhtml settings
     */
    public function getCustomerGroups()
    {
        $customerGroup = array();

        $customer_group = new Mage_Customer_Model_Group();
        $allGroups  = $customer_group->getCollection()->toOptionHash();
        foreach($allGroups as $key=>$allGroup){
            $customerGroup[$key] = array('value'=>$key,'label'=>$allGroup);
        }

        return $customerGroup;
    }


    /*
     * get list of CMS pages for adminhtml settings
     */
    public function getCmsPages()
    {
        $cmsPages = array();

        $pageCollection = Mage::getModel('cms/page')->getCollection()->addFieldToFilter('is_active', '1')->getData();

        foreach($pageCollection as $page){
            $cmsPages[$page['page_id']] = $page['title'];
        }

        asort($cmsPages);
        return $cmsPages;
    }


    /*
     * get list of CMS blocks for adminhtml settings
     */
    public function getCmsBlocks()
    {
        $cmsBlocks = array();
        $cmsBlocks[-1] = '- Do not replace -';

        $pageCollection = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('is_active', '1')->getData();

        foreach($pageCollection as $page){
            $cmsBlocks[$page['block_id']] = $page['title'];
        }

        asort($cmsBlocks);
        return $cmsBlocks;
    }


    /*
     *  get active rules for current customer
     */
    public function getActiveRules($params = false)
    {
        $session = Mage::getSingleton('customer/session');
        $groupId = $session->getCustomerGroupId();
        $activeRules = Mage::getModel('amgroupcat/rules')->getActiveRules($groupId, $params);

        return $activeRules;
    }


    /*
     *  get active rules for current product
     */
    public function getActiveRulesForProduct($productId,$params = false)
    {
        $session = Mage::getSingleton('customer/session');
        $groupId = $session->getCustomerGroupId();
        $activeRules = Mage::getModel('amgroupcat/rules')->getActiveRulesForProduct($productId,$groupId, $params);

        return $activeRules;
    }

    /*
     *  get active rules for hiding price for current product
     */
    public function getActiveRulesForProductPrice($productId,$params = false)
    {
        $session = Mage::getSingleton('customer/session');
        $groupId = $session->getCustomerGroupId();
        $activeRules = Mage::getModel('amgroupcat/rules')->getActiveRulesForProductPrice($productId,$groupId, $params);

        return $activeRules;
    }
}