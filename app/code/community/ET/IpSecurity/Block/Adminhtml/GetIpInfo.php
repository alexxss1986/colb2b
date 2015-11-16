<?php
/**
 * NOTICE OF LICENSE
 *
 * You may not sell, sub-license, rent or lease
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package    ET_IpSecurity
 * @copyright  Copyright (c) 2014 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
 */

class ET_IpSecurity_Block_Adminhtml_GetIpInfo extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     * shows in admin panel which ip address returns each method
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('etipsecurity');
        $result = $helper->__('Below is a list of standard variables where the server can '
            . 'store the IP address of the visitor, and what each of these variables contains on your server:<br><br>');
        $ip = '';
        $getIpMethodArray = Mage::getModel('etipsecurity/ipVariable')->getOptionArray();
        foreach ($getIpMethodArray as $key=>$value) {
            $ip = (isset($_SERVER[$value])) ? $_SERVER[$value] : $helper->__('Nothing');
            $result .= ' <b>' . $key . '</b> ' .
                $helper->__('returns') .
                '<b> ' . $ip . '</b><br>';
        }
        return $result;
    }
}