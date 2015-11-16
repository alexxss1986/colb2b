<?php

/**
 * Controller for self testing action
 * 
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_IndexController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {

        $msg = "Running UnityReports Self Test";
        $success = true;
        $helper = Mage::helper('unityreports');

        //check credentials are setup
        $apiKey = $helper->getApiKey();
        if (!$apiKey) {
            $msg.="<BR>API KEY is not setup";
            $success = false;
        }
        $apiSecret = $helper->getApiSecret();
        if (!$apiSecret) {
            $msg.="<BR>API SECRET is not setup";
            $success = false;
        }
        $licenseKey = $helper->getLicenseKey();
        if (!$licenseKey) {
            $msg.="<BR>LICENSE KEY is not setup";
            $success = false;
        }

        //check endpoint url is setup
        $endpoint = $helper->getEndpointUrl();
        if (!$endpoint) {
            $msg.="<BR>ENDPOINT URL is not setup";
            $success = false;
        }

        //check module is active
        if (!$helper->isActive()) {
            $msg.="<BR>Sync status is 'Off'";
            $success = false;
        }

        //test message exchange with endpoint
        $client = Mage::getSingleton('unityreports/utils')->getSoapClient();
        //get token
        $response = json_decode($client->getToken(
                        $apiKey, $apiSecret, $licenseKey
        ));
        if ($response->code != 'OK') {
            $msg.='<BR>Cannot get a valid Token: ' . $response->msg;
            $success = false;
        }

        Mage::log("Complete");

        if ($success) {
            $msg = $msg . "<br/> Testing completed successfully, if you are still experiencing difficulties please contact us on <a target='_blank' href='http://www.unityreports.com/contacts'>Unityreports.com</a>.";
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
        } else {
            $msg = $msg . "<br/> Testing failed,  please review the reported problems and if you need further help contact us on <a target='_blank' href='http://www.unityreports.com/contacts'>Unityreports.com</a>.";
            Mage::getSingleton('adminhtml/session')->addError($msg);
        }

        $this->_redirectReferer();
    }

   
}
