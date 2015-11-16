<?php

/**
 * Sends products data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Sync_Product extends Intelivemetrics_Unityreports_Model_Sync implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'product';

    protected function _isVisible($visibility) {
        return ($visibility == 1 ? 0 : 1);
    }

    protected function _isEnabled($status) {
        return ($status == 2 ? 0 : 1);
    }

    /**
     * Segna gli oggetti inviati
     * @param array $products
     */
    public function markSentItems(array $products) {
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/products');
        $now = date('Y-m-d H:i:s');
        try {
            foreach ($products as $product) {
                $query = "INSERT INTO $table (product_id,sents,last_sent_at) VALUES ({$product['id']},1,'{$now}')
                        ON DUPLICATE KEY UPDATE sents = sents+1,last_sent_at='{$now}';";
                $this->_getDb()->query($query);
                $this->_getDb()->closeConnection();
            }
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage(), Zend_Log::ERR);
            Mage::helper('unityreports')->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Salva oggetti sincronizzati
     * @param type $response
     */
    public function saveSyncedItems($response) {
        $helper = Mage::helper('unityreports');
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/products');
        try {
            foreach ($response as $productId) {
                $now = date('Y-m-d H:i:s');
                $this->_getDb()->query("INSERT INTO $table(product_id, synced, synced_at) 
                    VALUES($productId, 1, '$now') ON DUPLICATE KEY UPDATE synced = 1, synced_at='$now';");
                $this->_getDb()->closeConnection();
            }
            $counter = count($response);
            $helper->debug("Synced $counter products");
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
        }
    }

    /**
     * Esegue recupero dati degli prodotti
     * 
     * @param int $max_records numero massimo di records (indicativo)
     * @return array associativo contenente i dati
     */
    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');
        $now = date('Y-m-d H:i:s');
        try {
            //set store to admin otherwise it will use flat tables
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            Mage::app()->setCurrentStore($adminStore);

            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect(array('name', 'sku', 'type_id', 'created_at', 'updated_at', 'visibility', 'status'))
                    ->addAttributeToSort('updated_at', 'ASC');
            //add price 
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $adminStore);
            //add stock
            $collection->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left');
            //filter already sent
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/products');
            $collection->getSelect()
                    ->where("e.sku IS NOT NULL")
                    ->where("e.entity_id NOT IN (SELECT product_id FROM $table WHERE synced=1 OR sents>={$this->getMaxSents()} OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')<60)")
                    ->limit($limit)
            ;

            // se non ci sono record, esce
            if (count($collection) == 0) {
                $helper->debug('No product data found to sync', Zend_Log::INFO);
                return null;
            }

            // get the products collection
            $data = array();
            foreach ($collection as $product) {
                $data["item_" . $product->getEntityId()] = array(
                    'entity_name' => self::ENTITY_TYPE,
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'sku' => $product->getSku(),
                    'type' => $product->getTypeId(),
                    'created_at' => $product->getCreatedAt(),
                    'updated_at' => $product->getUpdatedAt(),
                    'price' => $product->getData('price'),
                    'qty' => $product->getData('qty'),
                    'visibility' => $this->_isVisible($product->getVisibility()),
                    'status' => $this->_isEnabled($product->getStatus()),
                );
                //if simple prod, try to find the parent

                if ($product->getTypeId() == "simple") {
                    //grouped products
                    $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
                    if (!$parentIds) {
                        //configurable products
                        $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
                    }
                    if (isset($parentIds[0])) {
                        //TODO: we only handle the case of simple products belonging to one parent. What happens in the other case?
                        $parentId = $parentIds[0];
                    }
                    $data['item_' . $product->getEntityId()]['parent_id'] = $parentId;
                }
            }


            return $data;
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
