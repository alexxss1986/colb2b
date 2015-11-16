<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * One page checkout success page
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Checkout_Block_Onepage_Success extends Mage_Core_Block_Template
{
    /**
     * @deprecated after 1.4.0.1
     */
    private $_order;

    /**
     * Retrieve identifier of created order
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getOrderId()
    {
        return $this->_getData('order_id');
    }

    /**
     * Check order print availability
     *
     * @return bool
     * @deprecated after 1.4.0.1
     */
    public function canPrint()
    {
        return $this->_getData('can_view_order');
    }

    /**
     * Get url for order detale print
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getPrintUrl()
    {
        return $this->_getData('print_url');
    }

    /**
     * Get url for view order details
     *
     * @return string
     * @deprecated after 1.4.0.1
     */
    public function getViewOrderUrl()
    {
        return $this->_getData('view_order_id');
    }

    /**
     * See if the order has state, visible on frontend
     *
     * @return bool
     */
    public function isOrderVisible()
    {
        return (bool)$this->_getData('is_order_visible');
    }

    /**
     * Getter for recurring profile view page
     *
     * @param $profile
     */
    public function getProfileUrl(Varien_Object $profile)
    {
        return $this->getUrl('sales/recurring_profile/view', array('profile' => $profile->getId()));
    }

    /**
     * Initialize data and prepare it for output
     */
    protected function _beforeToHtml()
    {
        $this->_prepareLastOrder();
        $this->_prepareLastBillingAgreement();
        $this->_prepareLastRecurringProfiles();
        return parent::_beforeToHtml();
    }

    /**
     * Get last order ID from session, fetch it and check whether it can be viewed, printed etc
     */
    protected function _prepareLastOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                $isVisible = !in_array($order->getState(),
                    Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());
                $this->addData(array(
                    'is_order_visible' => $isVisible,
                    'view_order_id' => $this->getUrl('sales/order/view/', array('order_id' => $orderId)),
                    'print_url' => $this->getUrl('sales/order/print', array('order_id'=> $orderId)),
                    'can_print_order' => $isVisible,
                    'can_view_order'  => Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible,
                    'order_id'  => $order->getIncrementId(),
                ));
            }
        }
    }

    /**
     * Prepare billing agreement data from an identifier in the session
     */
    protected function _prepareLastBillingAgreement()
    {
        $agreementId = Mage::getSingleton('checkout/session')->getLastBillingAgreementId();
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        if ($agreementId && $customerId) {
            $agreement = Mage::getModel('sales/billing_agreement')->load($agreementId);
            if ($agreement->getId() && $customerId == $agreement->getCustomerId()) {
                $this->addData(array(
                    'agreement_ref_id' => $agreement->getReferenceId(),
                    'agreement_url' => $this->getUrl('sales/billing_agreement/view',
                        array('agreement' => $agreementId)
                    ),
                ));
            }
        }
    }

    /**
     * Prepare recurring payment profiles from the session
     */
    protected function _prepareLastRecurringProfiles()
    {
        $profileIds = Mage::getSingleton('checkout/session')->getLastRecurringProfileIds();
        if ($profileIds && is_array($profileIds)) {
            $collection = Mage::getModel('sales/recurring_profile')->getCollection()
                ->addFieldToFilter('profile_id', array('in' => $profileIds))
            ;
            $profiles = array();
            foreach ($collection as $profile) {
                $profiles[] = $profile;
            }
            if ($profiles) {
                $this->setRecurringProfiles($profiles);
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $this->setCanViewProfiles(true);
                }
            }
        }
    }

    public function getInformation(){
        $orderId = $this->_getData('order_id');
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $tax_info = $order->getFullTaxInfo();
        if (isset($tax_info[0]['percent'])) {
            $tax_rate = $tax_info[0]['percent'];
        }
        else {
            $tax_rate=0;
        }
        $totale=$order->getGrandTotal() - $order->getShippingAmount();
        $array[0]=$totale;
        $array[1]=$order->getBillingAddress()->getFirstname();
        $array[2]=$orderId;
        $array[3]=$order->getGrandTotal();                     // Total transaction value (incl. tax and shipping)
        $array[4]=$order->getTaxAmount();
        $array[5]=$order->getShippingAmount();
        $array[6]=$order->getCouponCode();
        $array[7]=$order->getCustomerId();
        $array[8]=$tax_rate;
        return $array;
    }

    public function getProducts() {
        $orderId = $this->_getData('order_id');
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $_items = $order->getAllVisibleItems();
        $k = 0;
        foreach ($_items as $item) {
            $sku = $item->getSku();
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
            $brand=$product->getAttributeText("ca_brand");
            $nome=$product->getData("ca_name");
            $misura=$product->getAttributeText("ca_misura");
            $qtyToBeShipped = $item->getQtyOrdered() - $item->getQtyRefunded();
            $prezzo=(($item->getPriceInclTax() * $qtyToBeShipped) - ($item->getDiscountAmount()/$item->getQtyOrdered()*$qtyToBeShipped));
            $quantita=number_format($item->getQtyOrdered(),0);

            $category=$this->getLastCategory($product);
            $nome_sottocategoria=$category->getName();

            $array[$k][0]=$sku;
            $array[$k][1]=$brand;
            $array[$k][2]=$nome;

            $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
                ->getParentIdsByChild($product->getId());
            $productConf = Mage::getModel('catalog/product')->load($parentIds);
            $array[$k][3]=$productConf;
            $array[$k][4]=$misura;
            $array[$k][5]=$quantita;
            $array[$k][6]=$prezzo;
            $array[$k][7]=$nome_sottocategoria;
            $k=$k+1;
        }

        return $array;
    }

    function getLastCategory($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

        return $_category;
    }
}
