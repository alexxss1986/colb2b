<?php

/**
 * This is the Self test Button
 * 
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Block_Adminhtml_Button_Test extends Mage_Adminhtml_Block_System_Config_Form_Field {

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        return $this->_getAddRowButtonHtml($this->__('Test Configuration'));
    }

    protected function _getAddRowButtonHtml($title) {

        $buttonBlock = $this->getElement()->getForm()->getParent()->getLayout()->createBlock('adminhtml/widget_button');

        $_websiteCode = $buttonBlock->getRequest()->getParam('website');
        $params = array(
            'website' => $_websiteCode,
            // We add _store for the base url function, otherwise it will not correctly add the store code if configured
            '_store' => $_websiteCode ? $_websiteCode : Mage::app()->getDefaultStoreView()->getId()
        );

        $url = Mage::helper('adminhtml')->getUrl("unityreports", $params);

        return $this->getLayout()
                        ->createBlock('adminhtml/widget_button')
                        ->setType('button')
                        ->setLabel($this->__($title))
                        ->setOnClick("window.location.href='" . $url . "'")
                        ->toHtml();
    }

}
