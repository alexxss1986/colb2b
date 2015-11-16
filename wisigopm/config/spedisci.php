<?php

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

if (isset($_REQUEST['order_id'])) {
    try {

        $orderId = $_REQUEST['order_id'];

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);


        if ($order->canShip()) {
            $itemQty = $order->getItemsCollection()->count();
            $shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
            $shipment = new Mage_Sales_Model_Order_Shipment_Api();
                $shipmentId = $shipment->create($orderId);


        }

        if ($shipmentId != null) {
            echo "<script type='text/javascript'>alert('Spedizione creata con successo!'); location.replace('../ordini.php');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Errore nella creazione della spedizione!'); location.replace('../ordini.php');</script>";
        }
}
catch (Exception $e){
    echo "<script type='text/javascript'>alert('Errore nella creazione della spedizione! Contattare l\\'assistenza clienti'); location.replace('../ordini.php');</script>";
}

}
else {
    echo "<script type='text/javascript'>alert('Errore!'); location.replace('../ordini.php');</script>";
}