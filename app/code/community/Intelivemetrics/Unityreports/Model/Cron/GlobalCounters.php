<?php

/**
 * Daily count of synced objects
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Model_Cron_GlobalCounters extends Intelivemetrics_Unityreports_Model_Cron {

    /**
     * Called with magento cron
     * @return boolean
     * @assert () == true
     */
    public function runSync() {
        $helper = Mage::helper('unityreports');
        $helper->debug('*******NEW GLOBAL COUNTERS*******');
        try {
            //Check app status before syncing
            if (!$this->_appIsOk()) {
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

            //send data
            $data = $this->_getData();
            $blob = Intelivemetrics_Unityreports_Model_Utils::prepareDataForSending($data);
            $client->post(
                    $responseToken->msg, array(
                'type' => 'GLOBCOUNT',
                'data' => $blob,
                'license' => $helper->getLicenseKey()
                    )
            );

            return true;
        } catch (Exception $e) {
            $helper->debug($e, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return false;
        }
    }

    /**
     * Get all counters data
     * @return array associativo contenente i dati
     */
    protected function _getData() {
        $helper = Mage::helper('unityreports');
        $db = Mage::getSingleton('unityreports/utils')->getDb();

        try {
            $_counters = array(
                'orders' => 'sales/order',
                'invoices' => 'sales/invoice',
                'creditmemos' => 'sales/creditmemo',
                'customers' => 'customer/entity',
                'products' => 'catalog/product',
                'categories' => 'catalog/category',
            );
            foreach ($_counters as $counter => $entity) {
                $data[$counter] = $helper->getTableCount($entity);
            }

            //get active categories
            $categories = Mage::getModel('catalog/category')
                    ->getResourceCollection()
                    ->addAttributeToFilter(
                    'is_active', array('eq' => 1)
            );
            $data['active_categories'] = $categories->count();

            //get enabled products
            $products = Mage::getModel('catalog/product')
                    ->getResourceCollection()
                    ->addAttributeToFilter(
                    'status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            );
            $data['active_products'] = $products->count();

            //add counters date
            $data['counters_date'] = date('Y-m-d');

            return $data;
        } catch (Exception $ex) {
            $helper->debug($ex, Zend_Log::ERR);
            $helper->debug('FILE: ' . __FILE__ . 'LINE: ' . __LINE__);
            return null;
        }
    }

}

?>
