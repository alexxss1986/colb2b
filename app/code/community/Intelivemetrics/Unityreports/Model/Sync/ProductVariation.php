<?php

/**
 * Send product variation data (qty,price,status)
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Sync_ProductVariation extends Intelivemetrics_Unityreports_Model_Sync_Product implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'product_variation';

    /**
     * Segna gli oggetti inviati
     * @param array $products
     */
    public function markSentItems(array $products) {
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/products');
        $now = date('Y-m-d H:i:s');
        $helper = Mage::helper('unityreports');
        try {
            foreach ($products as $product) {
                $query = "UPDATE $table SET last_sent_at='{$now}' WHERE product_id={$product['id']};";
                $this->_getDb()->query($query);
                $this->_getDb()->closeConnection();
            }
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Mark updated objects
     * @param type $response
     */
    public function saveSyncedItems($response) {
        //product variations cannot be marked as processed
    }

    /**
     * Esegue recupero dati degli prodotti
     * 
     * @param int $limit numero massimo di records (indicativo)
     * @return array associativo contenente i dati
     */
    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');
        $today = date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        //this is lightweight data so we can send more at once
        $limit = 1000;

        try {
            //set store to admin otherwise it will use flat tables
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            Mage::app()->setCurrentStore($adminStore);

            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect(array('updated_at', 'visibility', 'status'))
                    ->addAttributeToSort('updated_at', 'ASC');
            //add price 
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $adminStore);
            //add stock
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
            //filter updated
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/products');
            $collection->joinField('synced_at', $table, 'synced_at', 'product_id=entity_id', "{{table}}.synced=1 AND DATE(last_sent_at)<'{$today}'", 'inner')
                    ->setPageSize($limit)
                    ->setCurPage(1);

            // se non ci sono record, esce
            if (count($collection) == 0) {
                $helper->debug('No product variations found to sync', Zend_Log::INFO);
                return null;
            }

            $prodData = array();
            foreach ($collection as $product) {
                $prodData["item_" . $product->getEntityId()] = array(
                    'id' => $product->getId(),
                    'updated_at' => $now,
                    'price' => $product->getData('price'),
                    'qty' => $product->getData('qty'),
                    'visibility' => $this->_isVisible($product->getVisibility()),
                    'status' => $this->_isEnabled($product->getStatus()),
                );
            }

            return $prodData;
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
