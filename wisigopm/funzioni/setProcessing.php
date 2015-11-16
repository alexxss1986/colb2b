<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$incrementId = '200000631'; //replace this with the increment id of your actual order
$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);

$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING);
$order->setStatus('processing');

$order->setBaseDiscountCanceled(0);
$order->setBaseShippingCanceled(0);
$order->setBaseSubtotalCanceled(0);
$order->setBaseTaxCanceled(0);
$order->setBaseTotalCanceled(0);
$order->setDiscountCanceled(0);
$order->setShippingCanceled(0);
$order->setSubtotalCanceled(0);
$order->setTaxCanceled(0);
$order->setTotalCanceled(0);

foreach($order->getAllItems() as $item){
    $item->setQtyCanceled(0);
    $item->setTaxCanceled(0);
    $item->setHiddenTaxCanceled(0);
    $item->save();
}

$order->save();