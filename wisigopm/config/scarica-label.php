<?php
include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$orderId=$_REQUEST['order_id'];
$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
$shipment = $order->getShipmentsCollection()->getFirstItem();
$labelContent = $shipment->getShippingLabel();

header("Content-type: application/pdf");
header("Content-length: ".strlen($labelContent));
header("Content-disposition: download; filename='ShippingLabels.pdf'");
echo $labelContent;
