<?php

/**
 * Tracks customer actions (visits, views,add to carts)
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Customer {

    const COOKIE_CUSTOMER_ID = 'UNITYREPORTS_CUSTOMER_ID';
    const COOKIE_CUSTOMER_VISIT = 'UNITYREPORTS_CUSTOMER_VISIT';
    const COOKIE_TIME = 31536000; //one year
    //customer actions
    const ACTION_VISIT = 'visit';
    const ACTION_ADD2CART = 'add2cart';
    const ACTION_VIEW = 'view';

    public function isKnown() {
        $known = !(empty($_COOKIE[self::COOKIE_CUSTOMER_ID])) && !(empty($_COOKIE[self::COOKIE_CUSTOMER_VISIT]));
        return ($known);
    }

    public function getCustomerId() {
        return $_COOKIE[self::COOKIE_CUSTOMER_ID];
    }

    public function canBeTracked() {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function addCookies($customerId) {
        setcookie(self::COOKIE_CUSTOMER_ID, $customerId, time() + self::COOKIE_TIME);
        $_COOKIE[self::COOKIE_CUSTOMER_ID] = $customerId;
        setcookie(self::COOKIE_CUSTOMER_VISIT, $this->_today(), time() + self::COOKIE_TIME);
        $_COOKIE[self::COOKIE_CUSTOMER_VISIT] = $customerId;
    }

    public function trackVisit() {
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        if (!$customerId) {
            return false;
        }
        //setup tracking cookies
        $this->addCookies($customerId);
        //log to db
        $action = self::ACTION_VISIT;
        $query = "INSERT INTO {$this->_getTable()}(customer_id,action_code,action_date,action_time) "
                . "VALUES ('{$customerId}','{$action}',NOW(),NOW());";
        try {
            Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
        } catch (Exception $ex) {
        }

        return true;
    }

    public function trackAdd2cart($sku) {
        $action = self::ACTION_ADD2CART;
        $query = "INSERT INTO {$this->_getTable()}(customer_id,action_code,action_desc,action_date,action_time) "
                . "VALUES ('{$this->getCustomerId()}','{$action}','{$sku}',NOW(),NOW());";
        try {
            Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
        } catch (Exception $ex) {
        }
    }

    public function trackView($sku) {
        $action = self::ACTION_VIEW;
        $query = "INSERT INTO {$this->_getTable()}(customer_id,action_code,action_desc,action_date,action_time) "
                . "VALUES ('{$this->getCustomerId()}','{$action}','{$sku}',NOW(),NOW());";
        try {
            Mage::getSingleton('unityreports/utils')->getDb()->query($query);
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
        } catch (Exception $ex) {
        }
    }

    public function visitWasTracked() {
        return (!empty($_COOKIE[self::COOKIE_CUSTOMER_VISIT]) && ($_COOKIE[self::COOKIE_CUSTOMER_VISIT] == $this->_today()));
    }

    protected function _today() {
        return date('Ymd');
    }

    protected function _getTable() {
        return Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customer_actions');
    }

}
