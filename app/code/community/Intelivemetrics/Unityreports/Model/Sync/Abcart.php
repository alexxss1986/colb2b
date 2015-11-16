<?php

/**
 * Send ab carts data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Sync_Abcart extends Intelivemetrics_Unityreports_Model_Sync implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'abcart';

    public function runSync() {
        $helper = Mage::helper('unityreports');
        if (!$helper->isActive()) {
            $helper->debug('Sync is deactivated');
            return false;
        }

        try {
            $client = $this->_getClient();
            
            //add some tracing
            if(function_exists('newrelic_add_custom_parameter')){
                newrelic_add_custom_parameter (sync_type, self::ENTITY_TYPE);
                newrelic_add_custom_parameter (sync_max, Intelivemetrics_Unityreports_Model_Utils::getMaxItemsPerSync());
            }
            
            // get data
            $abcarts = $this->_getData(Intelivemetrics_Unityreports_Model_Utils::getMaxItemsPerSync());
            if (is_null($abcarts)) {
                return self::NOTHING_TO_SYNC;
            }
            
            //get token
            $response = json_decode($client->getToken(
                            $helper->getApiKey(), $helper->getApiSecret(), $helper->getLicenseKey()
            ));
            if ($response->code != 'OK') {
                $helper->debug('Cannot get a valid Token.'.$response->msg);
                return false;
            }

            //see bellow, we don't mark them at this stage, but earlier
            $this->markSentItems($abcarts);
            
            //send data
            $blob = Intelivemetrics_Unityreports_Model_Utils::prepareDataForSending($abcarts);
            $client->post(
                    $response->msg, array(
                'type' => 'SYNC',
                'data' => $blob,
                'license' => $helper->getLicenseKey(),
                'entity' => self::ENTITY_TYPE
                    )
            );
            $helper->debug('Sending '.count($abcarts) .' '.self::ENTITY_TYPE);

            return true;
        } catch (Exception $e) {
            $helper->debug($e, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return false;
        }
    }

    /**
     * Segna gli oggetti inviati
     * @param array $items
     */
    public function markSentItems(array $quotes) {
        $now = date('Y-m-d H:i:s');
        $abcartsTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/abcarts');
        try {
            foreach ($quotes as $quote) {
                $query = "INSERT INTO $abcartsTable (entity_id,sents,last_sent_at) VALUES ({$quote['id']},1,'{$now}')
                        ON DUPLICATE KEY UPDATE sents = sents+1,last_sent_at='{$now}';";
                $this->_getDb()->query($query);
                $this->_getDb()->closeConnection();
            }
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage());
        }
    }

    /**
     * Salva oggetti sincronizzati
     * @param type $response
     */
    public function saveSyncedItems($response) {
        $helper = Mage::helper('unityreports');
        $abcartsTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/abcarts');
        try {
            foreach ($response as $entityId) {
                $now = date('Y-m-d H:i:s');
                $this->_getDb()->query("INSERT INTO $abcartsTable(entity_id, synced, synced_at) 
                    VALUES($entityId, 1, '$now') ON DUPLICATE KEY UPDATE synced = 1, synced_at='$now';");
                $this->_getDb()->closeConnection();
            }
            $counter = count($response);
            $helper->debug("Synced $counter quotes");
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage());
        }
    }

    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');
        $now = date('Y-m-d H:i:s');
        try {
            $abcartsTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/abcarts');
            $collection = Mage::getModel('sales/quote')->getCollection();
            $collection->getSelect()
                    ->where("items_count>0")
                    ->where("DATE( `created_at` ) < DATE_SUB( CURDATE( ) , INTERVAL 1 DAY )") //don't sync todays quotes because they may become sales
                    ->where("DATE( `created_at` ) > DATE_SUB( CURDATE( ) , INTERVAL 14 DAY )") //don't sync quotes more than 14 days old
                    ->where("main_table.entity_id NOT IN (SELECT entity_id FROM $abcartsTable WHERE synced=1 OR sents>={$this->getMaxSents()} OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')<60)")
                    ->order('updated_at ASC') //from oldest to newest
                    ->limit($limit)
            ;
//            $helper->debug($collection->getSelectSql()->__toString(), Zend_Log::DEBUG);

            //no records found, exit
            if (count($collection) == 0) {
                $helper->debug('No quotes found to sync', Zend_Log::INFO);
                return null;
            }

            //process data
            $quotes = array();
            foreach ($collection as $quote) {
                $_quote = array(
                    'id' => $quote->getId(),
                    'store_id' => $quote->getStoreId(),
                    'created_at' => $quote->getCreatedAt(),
                    'items' => array()
                );

                foreach ($quote->getItemsCollection() as $item) {
                    try {
                        //export only simple products
                        if ($item->getParentItem()) {
                            continue;
                        }

                        $_quote['items']['item_' . $item->getProductId()] = array(
                            'id' => $item->getProductId(),
                            'price' => $item->getPrice(),
                            'sku' => $item->getSku(),
                            'name' => $item->getName(),
                        );
                    } catch (Exception $ex) {
                        echo $ex;
                    }
                }

                $quotes["quote_" . $quote->getId()] = $_quote;
            }

            return $quotes;
        } catch (Exception $ex) {
            $helper->debug($ex, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
