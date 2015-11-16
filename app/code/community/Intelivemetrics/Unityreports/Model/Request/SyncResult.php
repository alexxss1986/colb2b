<?php

/**
 * Store sync result action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Request_SyncResult extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    public function execute($settings=array()) {
        Intelivemetrics_Unityreports_Model_Utils::log(__METHOD__);

        switch($settings['entity']){
            case Intelivemetrics_Unityreports_Model_Sync_Order::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Order();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_Invoice::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Invoice();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_Customer::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Customer();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_CustomerAction::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_CustomerAction();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_Abcart::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Abcart();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_Creditnote::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Creditnote();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_Product::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_Product();
                $s->saveSyncedItems($settings['data']);
                break;
            case Intelivemetrics_Unityreports_Model_Sync_ProductVariation::ENTITY_TYPE:
                $s = new Intelivemetrics_Unityreports_Model_Sync_ProductVariation();
                $s->saveSyncedItems($settings['data']);
                break;
        }
        //alles gut
        return $out = array(
            'done' => true
        );
    }

}

?>
