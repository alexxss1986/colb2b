<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$incrementId = '200000631'; //replace this with the increment id of your actual order
$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);

//Delete Credit Memo
$creditMemos = $order->getCreditmemosCollection();
foreach($creditMemos as $cm){	//cancel each credit memo for the order
    $state = $cm->getState();
    if($state == 3){//Cancled
        continue;
    }
    $cm->cancel()
        ->save()
        ->getOrder()->save();	//Needed to save the order to apply the canceled credit memo to all order items.

    $cm->delete();
}
