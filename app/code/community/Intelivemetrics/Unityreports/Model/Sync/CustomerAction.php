<?php

/**
 * Sends customers action (visit, add to carts,etc) data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Sync_CustomerAction 
extends Intelivemetrics_Unityreports_Model_Sync 
implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'customer_action';

    /**
     * Segna gli oggetti inviati
     * @param array $actions
     */
    public function markSentItems(array $actions) {
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customer_actions');
        $now = date('Y-m-d H:i:s');
        try {
            foreach ($actions as $action) {
                $query = "UPDATE $table SET last_sent_at='{$now}', sents=sents+1 WHERE id='{$action['id']}'";
                $this->_getDb()->query($query);
                $this->_getDb()->closeConnection();
            }
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
        }
    }

    /**
     * Salva oggetti sincronizzati
     * @param type $response
     */
    public function saveSyncedItems($response) {
        $helper = Mage::helper('unityreports');
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customer_actions');
        try {
            foreach ($response as $actionId) {
                $now = date('Y-m-d H:i:s');
                $query = "UPDATE $table SET synced_at='{$now}',synced='1' WHERE id='{$actionId}'";
                $this->_getDb()->query($query);
                $this->_getDb()->closeConnection();
            }
            $counter = (int) count($response);
            $helper->debug("Synced $counter customer actions");
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
        }
    }

    /**
     * Get data for sync
     * 
     * @param int $max_records numero massimo di records (indicativo)
     * @return array associativo contenente i dati
     */
    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');
        $now = date('Y-m-d H:i:s');
        try {
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customer_actions');
            $query = "SELECT *FROM {$table} "
                    . "WHERE synced=0 AND sents<{$this->getMaxSents()} AND (TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')>60 OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}') IS NULL)"
                    . "ORDER BY action_date ASC, action_time ASC "
                    . "LIMIT 0,{$limit}";
            $rows = Mage::getSingleton('unityreports/utils')->getDb()->query($query)->fetchAll();

            // se non ci sono record, esce
            if (count($rows) == 0) {
                $helper->debug('No customer actions data found to sync', Zend_Log::INFO);
                Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
                return null;
            }

            $data = array();
            foreach ($rows as $row) {
                $customerData = array(
                    'entity_name' => self::ENTITY_TYPE,
                    'id' => $row['id'],
                    'customer_id' => $row['customer_id'],
                    'action_code' => $row['action_code'],
                    'action_desc' => $row['action_desc'],
                    'action_date' => $row['action_date'],
                    'action_time' => $row['action_time']
                );

                $data[] = $customerData;
            }
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
            return $data;
        } catch (Exception $ex) {
            Mage::getSingleton('unityreports/utils')->getDb()->closeConnection();
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return null;
        }
    }
}
    