<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$order = Mage::getModel('sales/order')->loadByIncrementId("200000209");
if ($order->getTotalPaid() == 0) {
    $invoice = $order->prepareInvoice();
    $invoice->register()->capture();
    Mage::getModel('core/resource_transaction')
        ->addObject($invoice)
        ->addObject($invoice->getOrder())
        ->save();
    $order->save();
}