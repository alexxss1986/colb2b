<?php

/**
 * Send customers data
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */


class Intelivemetrics_Unityreports_Model_Sync_Customer extends Intelivemetrics_Unityreports_Model_Sync implements Intelivemetrics_Unityreports_Model_Sync_Interface {

    const ENTITY_TYPE = 'customer';

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
     * @param array $items
     */
    public function markSentItems(array $items) {
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customers');
        $now = date('Y-m-d H:i:s');
        try {
            foreach ($items as $item) {
                $query = "INSERT INTO $table (customer_id,sents,last_sent_at) VALUES ({$item['id']},1,'{$now}')
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
        $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customers');
        try {
            foreach ($response as $incrementId) {
                $now = date('Y-m-d H:i:s');
                $this->_getDb()->query("INSERT INTO $table(customer_id, synced, synced_at) 
                    VALUES($incrementId, 1, '$now') ON DUPLICATE KEY UPDATE synced = 1, synced_at='$now';");
                $this->_getDb()->closeConnection();
            }
            $counter = count($response);
            $helper->debug("Sincronizzati $counter clienti");
        } catch (Exception $ex) {
            $helper->debug($ex->getMessage(), Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__.'LINE: ' . __LINE__);
        }
    }

    /**
     * Esegue recupero dati degli ordini
     * 
     * @param date $last_imp_date ultima data di riferimento dll'ultima esportazione
     * @param int $max_records numero massimo di records (indicativo)
     * @return array associativo contenente i dati
     */
    protected function _getData($limit) {
        $helper = Mage::helper('unityreports');
        $now = date('Y-m-d H:i:s');
        try {
            $table = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/customers');
            $campaignsTable = Intelivemetrics_Unityreports_Model_Utils::getTableName('unityreports/campaigns');
            $collection = Mage::getModel('customer/customer')
                    ->getCollection()
                    ->addAttributeToSelect('*');
            $collection->getSelect()
                     ->joinLeft(
                            array('campaigns' => $campaignsTable), "entity_id=campaigns.id AND campaigns.type='customer'", array('source', 'medium', 'content', 'campaign')
                    )
                    ->where("entity_id NOT IN (SELECT customer_id FROM $table WHERE synced=1 OR sents>={$this->getMaxSents()} OR TIMESTAMPDIFF(MINUTE,last_sent_at,'{$now}')<60)")
                    ->limit($limit)
            ;

            // se non ci sono record, esce
            if (count($collection) == 0) {
                $helper->debug('No customer data found to sync', Zend_Log::INFO);
                return null;
            }

            $data = array();
            foreach ($collection as $customer) {
                $customerData = array(
                    'entity_name' => self::ENTITY_TYPE,
                    'id' => $customer->getId(),
                    'name' => $customer->getName(),
                    'dob' => $customer->getDob(),
                    'email' => $customer->getEmail(),
                    'group' => $this->_getGroupCode($customer->getGroupId()),
                    'gender' => $customer->getGender(),
                    'source' => $customer->getSource(),
                    'medium' => $customer->getMedium(),
                    'content' => $customer->getContent(),
                    'campaign' => $customer->getCampaign(),
                    'created_at' => $customer->getCreatedAt(),
                );

                // indirizzo di fatturazione
                if (is_object($address = $customer->getDefaultBillingAddress())) {
                    $customerData['bill_country'] = $address->getCountryId();
                }

                // indirizzo di spedizione
                if (is_object($address = $customer->getDefaultShippingAddress())) {
                    $customerData['ship_country'] = $address->getCountryId();
                }

                $data["customer_" . $customer->getId()] = $customerData;
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
