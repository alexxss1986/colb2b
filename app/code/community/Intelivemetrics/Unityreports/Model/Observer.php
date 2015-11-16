<?php

/**
 * Observer class
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Observer {

    protected function _saveCampaignInfo($id, $source, $medium, $content, $campaign, $type = 'order') {
        $db = Mage::getSingleton('unityreports/utils')->getDb();
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/campaigns');
        $query = "INSERT IGNORE INTO {$table}(id,source,medium,content,campaign,type) VALUES
                                ('{$id}','{$source}','{$medium}','{$content}','{$campaign}','{$type}')";
        $db->query($query);

        $db->closeConnection();
    }

    /**
     * Track order aquisition
     * @param type $observer
     */
    public function orderAddCampaignInfo($observer) {
        $order = $observer->getEvent()->getOrder();
        try {
            $utmz = Mage::getModel('unityreports/utmz');
            if ($utmz->utmz) {
                $this->_saveCampaignInfo($order->getId(), $utmz->utmz_source, $utmz->utmz_medium, $utmz->utmz_content, $utmz->utmz_campaign);
            }
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }
    
     /**
     * Track customer acquisition
     * @param type $observer
     */
    public function customerAddCampaignInfo($observer) {
        $customer = $observer->getEvent()->getCustomer();
        try {
            $utmz = Mage::getModel('unityreports/utmz');
            if ($utmz->utmz) {
                $this->_saveCampaignInfo($customer->getId(), $utmz->utmz_source, $utmz->utmz_medium, $utmz->utmz_content, $utmz->utmz_campaign, 'customer');
            }
        } catch (Exception $ex) {
            //don't log these error because it will fire on each customer update
        }
    }

    /**
     * Track order aquisition
     * @param type $observer
     */
    public function orderSaveAfterAdmin($observer) {
        $order = $observer->getEvent()->getOrder();
        $source = "(direct)";
        $campaign = "(direct)";
        $medium = "admin";
        $content = "";
        try {
            $this->_saveCampaignInfo($order->getId(), $source, $medium, $content, $campaign);
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Track unique product views
     * @param type $observer
     */
    public function productUpdateViews($observer) {
        try {
            $product = $observer->getEvent()->getProduct();
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/product_counters');
            $prodKey = 'PROD' . $product->getEntityId();
            if (!isset($_SESSION[$prodKey]) || $_SESSION[$prodKey] != 1) {
                $query = "INSERT INTO $table (product_id,unique_views,last_updated_at) VALUES ({$product->getEntityId()},1,NOW())
                            ON DUPLICATE KEY UPDATE unique_views = unique_views+1, last_updated_at=NOW();";
                Mage::getSingleton('unityreports/utils')->getDb()->query($query);
                $_SESSION[$prodKey] = 1;

                //track customer action
                $customer = Mage::getModel('unityreports/customer');
                if ($customer->isKnown()) {
                    $customer->trackView($product->getSku());
                }
            }

            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Track add to cart
     * @param type $observer
     */
    public function productAddCart($observer) {
        try {
            $product = $observer->getEvent()->getProduct();
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/product_counters');
            $query = "INSERT INTO $table (product_id,addtocarts,last_updated_at) VALUES ({$product->getEntityId()},1,NOW())
                        ON DUPLICATE KEY UPDATE addtocarts = addtocarts+1,last_updated_at=NOW();";
            Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            //track customer action
            $customer = Mage::getModel('unityreports/customer');
            if ($customer->isKnown()) {
                $customer->trackAdd2cart($product->getSku());
            }
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Track customer visits
     * @param type $observer
     */
    public function customerTrack($observer) {
        try {
            $customer = Mage::getModel('unityreports/customer');
            if ((!$customer->isKnown() || !$customer->visitWasTracked()) && $customer->canBeTracked()) {
                $customer->trackVisit();
            }
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }


}

?>
