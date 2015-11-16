<?php

class Magestore_Onestepcheckout_Helper_Rewrite_Checkout_Url extends Mage_Checkout_Helper_Url {
	
	public function getCheckoutUrl()
	{
		if(Mage::helper('onestepcheckout')->enabledOnestepcheckout() && Mage::helper('core')->isModuleOutputEnabled('Magestore_Onestepcheckout') )
			return Mage::getUrl('onestepcheckout/index', array('_secure' => true));
		else
			return $this->_getUrl('checkout/onepage');
    }
  

}