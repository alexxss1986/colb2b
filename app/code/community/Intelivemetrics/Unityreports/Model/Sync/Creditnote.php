<?php

/**
 * Send creditmemos data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Sync_Creditnote extends Intelivemetrics_Unityreports_Model_Sync implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'sales_creditnote';

    /**
     * Segna gli oggetti inviati
     * @param array $items
     */
    public function markSentItems(array $items) {
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/creditnotes');
        $now = date('Y-m-d H:i:s');
        try {
            foreach ($items as $item) {
                $query = "INSERT INTO $table (increment_id,sents,last_sent_at) VALUES ('{$item['increment_id']}',1,'{$now}')
                        ON DUPLICATE KEY UPDATE sents = sents+1,last_sent_at='{$now}';";
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
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/creditnotes');
        try {
            foreach ($response as $incrementId) {
                $now = date('Y-m-d H:i:s');
                $this->_getDb()->query("INSERT INTO $table(increment_id, synced, synced_at) 
                    VALUES('$incrementId', 1, '$now') ON DUPLICATE KEY UPDATE synced = 1, synced_at='$now';");
                $this->_getDb()->closeConnection();
            }
            $counter = count($response);
            $helper->debug("Synced $counter creditmemos");
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
        }
    }

    /**
     * Esegue recupero dati 
     * @return array associativo contenente i dati
     */
    protected function _getData( $limit) {
        $helper = Mage::helper('unityreports');
        $creditnotesTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/creditnotes');
        $ordersTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/orders');
        $ordersTableMage = Intelivemetrics_Unityreports_Model_Utils::getTableName('sales_flat_order');
        $now = date('Y-m-d H:i:s');
        try {
            $collection = Mage::getModel('sales/order_creditmemo')->getCollection()
                    ->addAttributeToSelect('*');
            $collection->getSelect()
                    ->joinLeft(array('orders' => $ordersTableMage), "orders.entity_id=main_table.order_id", array('o_increment_id' => 'increment_id'))
                    ->where("main_table.increment_id NOT IN (SELECT increment_id FROM $creditnotesTable WHERE synced=1 OR sents>={$this->getMaxSents()} OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')<60)")
                    ->where("orders.increment_id IN (SELECT increment_id FROM $ordersTable WHERE synced=1)")
                    ->limit($limit)
            ;

            // se non ci sono record, esce
            if (count($collection) == 0) {
                $helper->debug('No creditnotes data found to sync', Zend_Log::INFO);
                return null;
            }

            $data = array();
            foreach ($collection as $creditnote) {
                $attributes = $creditnote->getData();
                $currency = $attributes['order_currency_code'];
                $order_fields = array(
                    'entity_name' => self::ENTITY_TYPE,
                    'id' => $creditnote->getId(),
                    'increment_id' => $creditnote->getIncrementId(),
                    'order_id' => $creditnote->getOrderId(),
                    'grand_total' => $attributes['grand_total'],
                    'shipping_amount' => $attributes['shipping_amount'],
                    'shipping_tax_amount' => $attributes['shipping_tax_amount'],
                    'subtotal' => $attributes['subtotal'],
                    'discount_amount' => $attributes['discount_amount'],
                    'tax_amount' => $attributes['tax_amount'],
                    'currency_code' => $currency,
                    'created_at' => $attributes['created_at'],
                );

                //get items info
                foreach ($creditnote->getItemsCollection() as $item) {
                    $order_fields['items'][] = array('id' => $item->getProductId(), 'qty' => $item->getQty());
                }

                $data["creditnote_" . $creditnote->getIncrementId()] = $order_fields;
            }

            return $data;
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
