<?php
class Magestore_Onestepcheckout_Block_Onepage_Link extends Mage_Checkout_Block_Onepage_Link
{
    public function getCheckoutUrl()
    {
		if(Mage::helper('onestepcheckout')->enabledOnestepcheckout() && Mage::helper('core')->isModuleOutputEnabled('Magestore_Onestepcheckout') )
			return Mage::getUrl('onestepcheckout/index', array('_secure' => true));
		else
			return $this->getUrl('checkout/onepage', array('_secure'=>true));
    }

}

?>