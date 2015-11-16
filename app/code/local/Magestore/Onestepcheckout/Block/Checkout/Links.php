<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Links block
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magestore_Onestepcheckout_Block_Checkout_Links extends Mage_Checkout_Block_Links
{

    public function addCheckoutLink()
    {
		$parentBlock = $this->getParentBlock();
		if(Mage::helper('onestepcheckout')->enabledOnestepcheckout() && Mage::helper('core')->isModuleOutputEnabled('Magestore_Onestepcheckout') ){
			$text = $this->__('Checkout');
			if($parentBlock)
				$parentBlock->addLink(
					$text, 'onestepcheckout/index', $text,
					true, array('_secure' => true), 60, null,
					'class="top-link-checkout"'
				);
		}else{
			if (!$this->helper('checkout')->canOnepageCheckout()) {
				return $this;
			}

        
			if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
				$text = $this->__('Checkout');
				$parentBlock->addLink(
					$text, 'checkout', $text,
					true, array('_secure' => true), 60, null,
					'class="top-link-checkout"'
				);
			}
		}
        
        return $this;
    }
}
