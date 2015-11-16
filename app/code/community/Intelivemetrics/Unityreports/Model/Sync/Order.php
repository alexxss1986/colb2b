<?php


/**
 * Send orders data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Sync_Order extends Intelivemetrics_Unityreports_Model_Sync implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'sales_order';

    protected $_groups = array();

    protected function _getGroupCode($groupId) {
        if (!isset($this->_groups[$groupId])) {
            $group = Mage::getModel('customer/group')->load($groupId);
            if (is_object($group)) {
                $this->_groups[$groupId] = $group->getCode();
            } else {
                $this->_groups[$groupId] = '';
            }
        }

        return $this->_groups[$groupId];
    }

    /**
     * Segna gli oggetti inviati
     * @param array $orders
     */
    public function markSentItems(array $orders) {
        $ordersTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/orders');
        $now = date('Y-m-d H:i:s');
        try {
            foreach ($orders as $order) {
                $query = "INSERT INTO $ordersTable (increment_id,sents,last_sent_at) VALUES ('{$order['increment_id']}',1,'{$now}')
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
        $ordersTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/orders');
        try {
            foreach ($response as $incrementId) {
                $now = date('Y-m-d H:i:s');
                $this->_getDb()->query("INSERT INTO $ordersTable(increment_id, synced, synced_at) 
                    VALUES('$incrementId', 1, '$now') ON DUPLICATE KEY UPDATE synced = 1, synced_at='$now';");
                $this->_getDb()->closeConnection();
            }
            $counter = count($response);
            $helper->debug("Synced $counter orders");
        } catch (Exception $ex) {
            Mage::helper('unityreports')->debug($ex->getMessage());
        }
    }

    /**
     * Esegue recupero dati degli ordini
     * 
     * @param int $max_records numero massimo di records (indicativo)
     * @return array associativo contenente i dati
     */
    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');

        try {
            $campaignsTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/campaigns');
            $ordersTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/orders');
            $now = date('Y-m-d H:i:s');
            $collection = Mage::getModel('sales/order')->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToSort('updated_at', 'ASC');
            $collection->getSelect()
                    ->joinLeft(
                            array('campaigns' => $campaignsTable), "main_table.entity_id=campaigns.id AND campaigns.type='order'", array('source', 'medium', 'content', 'campaign')
                    )
                    ->where("main_table.increment_id NOT IN (SELECT increment_id FROM $ordersTable WHERE synced=1 OR sents>={$this->getMaxSents()} OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')<60)")
                    ->limit($limit)
            ;

            // nothing to sync get out
            if (count($collection) == 0) {
                $helper->debug('No order data found to sync', Zend_Log::INFO);
                return null;
            }

            // pack order data
            $data = array();
            $order_count = 0;
            $category = Mage::getModel('catalog/category');
            foreach ($collection as $order) {
                $attributes = $order->getData();
                
                try {
                    $currency = $attributes['order_currency_code'];

                    if (isset($attributes['group_id'])){
                        $groupId=$attributes['group_id'];
                    }
                    else {
                        $groupId="";
                    }
                    $order_fields = array(
                        'entity_name' => self::ENTITY_TYPE,
                        'id' => $order->getId(),
                        'increment_id' => $order->getIncrementId(),
                        'coupon_code' => $attributes['coupon_code'],
                        'store_id' => $attributes['store_id'],
                        'customer_id' => $attributes['customer_id'],
                        'customer_group' => $this->_getGroupCode($groupId),
                        'grand_total' => $attributes['grand_total'],
                        'shipping_amount' => $attributes['shipping_amount'],
                        'shipping_tax_amount' => $attributes['shipping_tax_amount'],
                        'subtotal' => $attributes['subtotal'],
                        'discount_amount' => $attributes['discount_amount'],
                        'tax_amount' => $attributes['tax_amount'],
                        'currency_code' => $currency,
                        'total_qty_ordered' => $attributes['total_qty_ordered'],
                        'created_at' => $attributes['created_at'],
                        'total_item_count' => $attributes['total_item_count'],
                        'status' => $attributes['status'],
                        'state' => $attributes['state'],
                        'shipping_description' => $attributes['shipping_description'],
                        'source' => $attributes['source'],
                        'medium' => $attributes['medium'],
                        'content' => $attributes['content'],
                        'campaign' => $attributes['campaign'],
                        'payment_method' => $order->getPayment()->getMethod()
                    );

                    // indirizzo di spedizione
                    $orderShippingAddress = $order->getShippingAddress();
                    if (!is_null($orderShippingAddress) && is_object($orderShippingAddress)) {
                        $shipping_arr = array();
                        $shipping_arr['postcode'] = $orderShippingAddress->getPostcode();
                        $shipping_arr['city'] = $orderShippingAddress->getCity();
                        $shipping_arr['region'] = $orderShippingAddress->getRegion();
                        $shipping_arr['country'] = $orderShippingAddress->getCountry();
                        $order_fields['shipping_address'] = $shipping_arr;
                    }

                    // indirizzo di fatturazione
                    $orderBillingAddress = $order->getBillingAddress();
                    if (!is_null($orderBillingAddress) && is_object($orderBillingAddress)) {
                        $billing_arr = array();
                        $billing_arr['postcode'] = $orderBillingAddress->getPostcode();
                        $billing_arr['city'] = $orderBillingAddress->getCity();
                        $billing_arr['region'] = $orderBillingAddress->getRegion();
                        $billing_arr['country'] = $orderBillingAddress->getCountry();
                        $order_fields['billing_address'] = $billing_arr;
                    }

                    // processa le righe dell'ordine
                    $items_arr = array();
                    foreach ($order->getAllItems() as $item) {
                        $_categories = array();

                        //export only simple products
                        if ($item->getParentItem()) {
                            continue;
                        }

                        $item_attribs = $item->getData();
                        $item_arr = array();
                        $item_arr['item_id'] = $item_attribs['product_id'];
                        $item_arr['order_id'] = $order->getId();
                        $item_arr['sku'] = $item_attribs['sku'];
                        $item_arr['name'] = $item_attribs['name'];
                        $item_arr['qty'] = $item_attribs['qty_ordered'];
                        $item_arr['price'] = $item_attribs['price'];
                        $item_arr['tax_amount'] = $item_attribs['tax_amount'];
                        $item_arr['product_type'] = $item_attribs['product_type'];
                        $item_arr['creation_date'] = $item_attribs['created_at'];
                        $item_arr['update_date'] = $item_attribs['updated_at'];

                        //recupera path categorie, solo della prima categoria associata
                        //TODO: what if no category info is available? put some fake cateogry like UNKNOWN
                        if ( ($product = $item->getProduct()) || ($product=Mage::getModel('catalog/product')->load($item->getProductId())) ) {
                            $mainCategory = $product->getCategoryCollection()->getFirstItem();
                            $ids = array_reverse($mainCategory->getPathIds());
                            $counter = 1;
                            foreach ($ids as $categoryId) {
                                //massimo 5 livelli di profondità
                                if ($counter > 5) {
                                    break;
                                }
                                if ($category->load($categoryId)) {
                                    $_categories[] = array(
                                        'id' => $category->getId(),
                                        'name' => $category->getName(),
                                    );
                                }
                                $counter++;
                            }
                            $item_arr['categories'] = $_categories;
                        }

                        // recupera le opzioni scelte
                        if ($item_attribs['product_type'] == 'configurable') {
                            $productOptions = $item->getProductOptions();
                            $superAttributeIds = array();
                            foreach ($productOptions['info_buyRequest']['super_attribute'] as $superId => $superValue) {
                                $superAttributeIds[] = $superId;
                            }
                            $option = array();
                            foreach ($productOptions['attributes_info'] as $index => $attribute) {
                                $attributeId = $superAttributeIds[$index];
                                $option = array(
                                    'attribute_id' => $attributeId,
                                    'label' => $attribute['label'],
                                    'value' => $attribute['value'],
                                );
                            }
                            $item_arr['options'][] = $option;
                        }

                        $items_arr['item_' . $item_attribs['item_id']] = $item_arr;
                    }
                    $order_fields['items'] = $items_arr;

                    $data["order_" . $order->getIncrementId()] = $order_fields;
                    $order_count++;
                } catch (Exception $ex) {
                    $helper->debug($ex->getMessage(), Zend_Log::ERR);
                    $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
                }
            }//end order loop

            return $data;
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
