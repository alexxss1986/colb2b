<?php

/**
 * Description of Track
 *
 * @author Roberto
 */
class Webgriffe_Tntpro_Model_Track extends Mage_Usa_Model_Shipping_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
/**
     * Code of the carrier
     *
     * @var string
     */
    const CODE = 'wgtntpro';

    /**
     * Code of the carrier
     *
     * @var string
     */
    protected $_code = self::CODE;

    public function getAllowedMethods()
    {
        return array();
    }

    protected function _doShipmentRequest(Varien_Object $request)
    {
        $ret = new Varien_Object();
        #$ret->setTrackingNumber('123');
        return $ret;
    }

    /**
     * Collect and get rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool|Mage_Shipping_Model_Rate_Result|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        return false;
    }

    public function getTracking($number) {
        $ret = Mage::getModel('shipping/tracking_result');

        $tracking = Mage::getModel('shipping/tracking_result_status');
        $tracking->setCarrier(self::CODE);
        $tracking->setCarrierTitle(Mage::getStoreConfig('carriers/wgtntpro/title'));
        $tracking->setTracking($number);
        $tracking->setUrl('http://www.tnt.it/tracking/getTrack?WT=1&ldv='.$number);
        #$tracking->addData($data);
        #$tracking->setErrorMessage($r);

        $ret->append($tracking);

        return $ret;
    }
}

