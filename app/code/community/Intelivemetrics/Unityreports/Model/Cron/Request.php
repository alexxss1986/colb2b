<?php

/**
 * Message exchanger cron
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Cron_Request extends Intelivemetrics_Unityreports_Model_Cron {
    //how many msgs to claim
    const CLAIM_LIMIT=10;

    //action code codes
    const ACTION_RESET_ALL = "reset_all";
    const ACTION_RESET_PRODUCTS = "reset_products";
    const ACTION_RESET_PRODUCT_COUNTERS = "reset_product_counters";
    const ACTION_RESET_ORDERS = "reset_orders";
    const ACTION_RESET_INVOICES = "reset_invoices";
    const ACTION_RESET_CREDITMEMOS = "reset_creditmemos";
    const ACTION_RESET_CUSTOMERS = "reset_customers";
    const ACTION_RESET_ABCARTS = "reset_abcarts";
    const ACTION_STOP_SYNC = "stop_sync";
    const ACTION_START_SYNC = "start_sync";
    const ACTION_HANDSHAKE = "handshake"; //get client system info
    const ACTION_GET_COUNTERS = "get_counters"; //get stats like: orders count, customers count, etc
    const ACTION_SYNC_VERY_SLOW = "set_speed_very_slow"; //controll sync speed
    const ACTION_SYNC_SLOW = "set_speed_slow";
    const ACTION_SYNC_NORMAL = "set_speed_normal";
    const ACTION_SYNC_FAST = "set_speed_fast";
    const ACTION_SYNC_VERY_FAST = "set_speed_very_fast";
    const ACTION_DIAGNOSE = "diagnose";
    const ACTION_SYNC_RES = "SYN_RES";
    const ACTION_DQ = "dq";

    //implemented action codes
    protected $_actions = array(
        self::ACTION_RESET_ALL,
        self::ACTION_RESET_PRODUCTS,
        self::ACTION_RESET_PRODUCT_COUNTERS,
        self::ACTION_RESET_ORDERS,
        self::ACTION_RESET_INVOICES,
        self::ACTION_RESET_CREDITMEMOS,
        self::ACTION_RESET_CUSTOMERS,
        self::ACTION_RESET_ABCARTS,
        self::ACTION_STOP_SYNC,
        self::ACTION_START_SYNC,
        self::ACTION_HANDSHAKE,
        self::ACTION_GET_COUNTERS,
        self::ACTION_SYNC_VERY_SLOW,
        self::ACTION_SYNC_SLOW,
        self::ACTION_SYNC_NORMAL,
        self::ACTION_SYNC_FAST,
        self::ACTION_SYNC_VERY_FAST,
        self::ACTION_DIAGNOSE,
        self::ACTION_SYNC_RES,
        self::ACTION_DQ
    );

    /**
     * Executes endpoint requested action
     * @param string $action
     */
    protected function _executeAction($action) {
        $actionCode = $action->type;
        if (!$actionCode) {
            Intelivemetrics_Unityreports_Model_Utils::log("Missing action code", Zend_Log::ERR);
            return false;
        }

        //check action is valid
        if (!in_array($actionCode, $this->_actions)) {
            Intelivemetrics_Unityreports_Model_Utils::log("Unknown action code $actionCode", Zend_Log::ERR);
            return false;
        }

        $actionKey = $action->data;
        Intelivemetrics_Unityreports_Model_Utils::log("Request for $actionCode", Zend_Log::INFO);

        //execute
        switch ($actionCode) {
            case self::ACTION_SYNC_RES:
                Mage::getModel('unityreports/request_syncResult')->execute(array('entity' => $action->entity, 'data' => $action->data));
                break;
            case self::ACTION_STOP_SYNC:
                $result = Mage::getModel('unityreports/request_stopSync')->execute();
                break;
            case self::ACTION_START_SYNC:
                $result = Mage::getModel('unityreports/request_startSync')->execute();
                break;
            case self::ACTION_RESET_ALL:
                $result = Mage::getModel('unityreports/request_resetAll')->execute();
                break;
            case self::ACTION_RESET_PRODUCTS:
                $result = Mage::getModel('unityreports/request_resetProducts')->execute();
                break;
            case self::ACTION_RESET_PRODUCT_COUNTERS:
                $result = Mage::getModel('unityreports/request_resetProductCounters')->execute();
                break;
            case self::ACTION_RESET_ORDERS:
                $result = Mage::getModel('unityreports/request_resetOrders')->execute();
                break;
            case self::ACTION_RESET_INVOICES:
                $result = Mage::getModel('unityreports/request_resetInvoices')->execute();
                break;
            case self::ACTION_RESET_CREDITMEMOS:
                $result = Mage::getModel('unityreports/request_resetCreditmemos')->execute();
                break;
            case self::ACTION_RESET_CUSTOMERS:
                $result = Mage::getModel('unityreports/request_resetCustomers')->execute();
                break;
            case self::ACTION_RESET_ABCARTS:
                $result = Mage::getModel('unityreports/request_resetAbcarts')->execute();
                break;
            case self::ACTION_SYNC_VERY_SLOW:
                $result = Mage::getModel('unityreports/request_setSpeed')->execute(array('very_slow'));
                break;
            case self::ACTION_SYNC_SLOW:
                $result = Mage::getModel('unityreports/request_setSpeed')->execute(array('slow'));
                break;
            case self::ACTION_SYNC_NORMAL:
                $result = Mage::getModel('unityreports/request_setSpeed')->execute(array('normal'));
                break;
            case self::ACTION_SYNC_FAST:
                $result = Mage::getModel('unityreports/request_setSpeed')->execute(array('fast'));
                break;
            case self::ACTION_SYNC_VERY_FAST:
                $result = Mage::getModel('unityreports/request_setSpeed')->execute(array('very_fast'));
                break;
            case self::ACTION_HANDSHAKE:
                $result = Mage::getModel('unityreports/request_handShake')->execute();
                break;
            case self::ACTION_GET_COUNTERS:
                $result = Mage::getModel('unityreports/request_getCounters')->execute();
                break;
            case self::ACTION_DIAGNOSE:
                $result = Mage::getModel('unityreports/request_diagnose')->execute();
                break;
            case self::ACTION_DQ:
                $result = Mage::getModel('unityreports/request_dq')->execute($action->params);
                break;
        }
        
        if(isset($result) && $result){
            $this->_sendResponse($result, $actionCode, $actionKey);
        }

        return true;
    }

    /**
     * periodicaly Query the endpoint for action requests
     * @assert () == true
     */
    public function check() {
        $helper = Mage::helper('unityreports');
        $helper->debug('*******NEW REQUEST CHECK*******');
        try {
            //Check app status before syncing. No active sync is required
            if (!$this->_appIsOk(false)) {
                $helper->debug('Endpoint is not receiving');
                return false;
            }

            //get a soap client
            $client = $this->_getClient();

            //get token
            $responseToken = json_decode($client->getToken(
                            $helper->getApiKey(), $helper->getApiSecret(), $helper->getLicenseKey()
            ));
            if ($responseToken->code != 'OK') {
                $helper->debug('Cannot get a valid Token.' . $responseToken->msg);
                return false;
            }

            //get a response/request msg
            $msg = $client->get($responseToken->msg, self::CLAIM_LIMIT);
            $response = json_decode($msg);
            if ($response->code != 'OK') {
                $helper->debug('Cannot get new messages.' . $response->msg);
                return false;
            }

            //execute action
            foreach($response->msg as $msg){
                $this->_executeAction($msg);
            }
            return true;
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return false;
        }
    }

    /**
     * Send action response to Endpoint
     * @param boolean $response (0:not done 1:done)
     * @param string $actionKey
     */
    protected function _sendResponse($response, $actionCode, $actionKey) {
        $helper = Mage::helper('unityreports');
        $client = $this->_getClient();
        try {
            //get token
            $responseToken = json_decode($client->getToken(
                            $helper->getApiKey(), $helper->getApiSecret(), $helper->getLicenseKey()
            ));
            if ($responseToken->code != 'OK') {
                $helper->debug('Cannot get a valid Token.' . $responseToken->msg);
                return false;
            }

            $blob = Intelivemetrics_Unityreports_Model_Utils::prepareDataForSending(array(
                'key'=>$actionKey,
                'code'=>$actionCode,
                'response'=>$response['done'],
                'info'=>(isset($response['info'])?$response['info']:null),
            ));
            $response = json_decode($client->post(
                   $responseToken->msg, array(
                        'type' => 'RESP',
                        'data' => $blob,
                        'license' => $helper->getLicenseKey()
                    )
            ));
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
        }
    }

}
