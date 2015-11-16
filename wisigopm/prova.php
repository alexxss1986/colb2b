<?php
require_once '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$order = Mage::getModel('sales/order')->loadByIncrementId("200000053");
$order->setData('state', "complete");
$order->setStatus("complete");
$history = $order->addStatusHistoryComment('Order was set to Complete by our automation tool.', false);
$history->setIsCustomerNotified(false);
$order->save();