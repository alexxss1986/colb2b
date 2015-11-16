<?php
	session_cache_limiter('nocache');
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dashboard | Wisigo Product Management</title>

<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- PLUGINS CSS -->
		
		
		<link href="assets/plugins/morris-chart/morris.min.css" rel="stylesheet">
		<link href="assets/plugins/slider/slider.min.css" rel="stylesheet">
		
		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/style-responsive.css" rel="stylesheet">
 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

        
</head>


<?php

if (isset($_SESSION['username'])){
	
		include("config/percorsoMage.php");
	require_once $MAGE;
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

    if ($_SESSION['livello']!=3) {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToFilter('type_id', array('eq' => "configurable"));

        $collection2 = Mage::getResourceModel('reports/order_collection')
            ->calculateSales();


        $collection2->load();
        $sales = $collection2->getFirstItem();
        $average = $sales->getAverage();
        $lifetime = $sales->getLifetime();

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter(
                array(
                    'status',
                    'status'
                ),
                array(
                    array('eq' => 'complete'),
                    array('eq' => 'processing')
                )
            );


        $anno_ordini = date("Y");

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-01-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-02-01'));


        $ordiniGennaio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleGennaio = array_sum($ordiniGennaio);
        $ordiniGennaio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingGennaio = array_sum($ordiniGennaio);
        $totaleGennaio = ($totaleGennaio - $shippingGennaio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-02-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-03-01'));

        $ordiniFebbraio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleFebbraio = array_sum($ordiniFebbraio);
        $ordiniFebbraio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingFebbraio = array_sum($ordiniFebbraio);
        $totaleFebbraio = ($totaleFebbraio - $shippingFebbraio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-03-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-04-01'));

        $ordiniMarzo = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleMarzo = array_sum($ordiniMarzo);
        $ordiniMarzo = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingMarzo = array_sum($ordiniMarzo);
        $totaleMarzo = ($totaleMarzo - $shippingMarzo) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-04-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-05-01'));

        $ordiniAprile = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleAprile = array_sum($ordiniAprile);

        $ordiniAprile = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingAprile = array_sum($ordiniAprile);

        $totaleAprile = ($totaleAprile - $shippingAprile) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-05-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-06-01'));

        $ordiniMaggio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleMaggio = array_sum($ordiniMaggio);
        $ordiniMaggio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingMaggio = array_sum($ordiniMaggio);
        $totaleMaggio = ($totaleMaggio - $shippingMaggio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-06-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-07-01'));

        $ordiniGiugno = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleGiugno = array_sum($ordiniGiugno);
        $ordiniGiugno = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingGiugno = array_sum($ordiniGiugno);
        $totaleGiugno = ($totaleGiugno - $shippingGiugno) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-07-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-08-01'));

        $ordiniLuglio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');

        $totaleLuglio = array_sum($ordiniLuglio);
        $ordiniLuglio = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingLuglio = array_sum($ordiniLuglio);
        $totaleLuglio = ($totaleLuglio - $shippingLuglio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-08-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-09-01'));

        $ordiniAgosto = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleAgosto = array_sum($ordiniAgosto);
        $ordiniAgosto = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingAgosto = array_sum($ordiniAgosto);
        $totaleAgosto = ($totaleAgosto - $shippingAgosto) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-09-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-10-01'));

        $ordiniSettembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleSettembre = array_sum($ordiniSettembre);
        $ordiniSettembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingSettembre = array_sum($ordiniSettembre);
        $totaleSettembre = ($totaleSettembre - $shippingSettembre) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-10-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-11-01'));

        $ordiniOttobre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleOttobre = array_sum($ordiniOttobre);
        $ordiniOttobre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingOttobre = array_sum($ordiniOttobre);
        $totaleOttobre = ($totaleOttobre - $shippingOttobre) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-11-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-12-01'));

        $ordiniNovembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleNovembre = array_sum($ordiniNovembre);
        $ordiniNovembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingNovembre = array_sum($ordiniNovembre);
        $totaleNovembre = ($totaleNovembre - $shippingNovembre) / 1.22;


        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-12-01'));
        $toDate = date('Y-m-d', strtotime(($anno_ordini + 1) . '-12-01'));

        $ordiniDicembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleDicembre = array_sum($ordiniDicembre);
        $ordiniDicembre = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingDicembre = array_sum($ordiniDicembre);
        $totaleDicembre = ($totaleDicembre - $shippingDicembre) / 1.22;


        $ordersITA = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 1);

        $ordersENG = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 2);

        $ordersPortali = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 1)
            ->addFieldToFilter('customer_group_id', 4);


        $ordersECommerce = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 1)
            ->addFieldToFilter('customer_group_id', 1);

        $ordersECommerce2 = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 2)
            ->addFieldToFilter('customer_group_id', 1);

        if (count($orders)>0) {
            $percOrdITA=(count($ordersITA)/count($orders))*100;
            $percOrdENG=(count($ordersENG)/count($orders))*100;

            $ordiniPortali=(count($ordersPortali)/count($orders))*100;
            $ordiniECommerce=(count($ordersECommerce)+count($ordersECommerce2))/count($orders)*100;
        }
        else {
            $percOrdITA=0;
            $percOrdENG=0;

            $ordiniPortali=0;
            $ordiniECommerce=0;

        }

    }
    else {
        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->setStoreId(3)
            ->addAttributeToFilter('type_id', array('eq' => "configurable"));

        $collection2 = Mage::getResourceModel('reports/order_collection')
            ->addFieldToFilter('store_id', 3)
            ->calculateSales();


        $collection2->load();
        $sales = $collection2->getFirstItem();
        $average = $sales->getAverage();
        $lifetime = $sales->getLifetime();

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter(
                array(
                    'status',
                    'status'
                ),
                array(
                    array('eq' => 'complete'),
                    array('eq' => 'processing')
                )
            )
            ->addFieldToFilter('store_id', 3);


        $anno_ordini = date("Y");

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-01-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-02-01'));


        $ordiniGennaio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleGennaio = array_sum($ordiniGennaio);
        $ordiniGennaio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingGennaio = array_sum($ordiniGennaio);
        $totaleGennaio = ($totaleGennaio - $shippingGennaio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-02-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-03-01'));

        $ordiniFebbraio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleFebbraio = array_sum($ordiniFebbraio);
        $ordiniFebbraio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingFebbraio = array_sum($ordiniFebbraio);
        $totaleFebbraio = ($totaleFebbraio - $shippingFebbraio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-03-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-04-01'));

        $ordiniMarzo = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleMarzo = array_sum($ordiniMarzo);
        $ordiniMarzo = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingMarzo = array_sum($ordiniMarzo);
        $totaleMarzo = ($totaleMarzo - $shippingMarzo) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-04-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-05-01'));

        $ordiniAprile = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleAprile = array_sum($ordiniAprile);

        $ordiniAprile = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingAprile = array_sum($ordiniAprile);

        $totaleAprile = ($totaleAprile - $shippingAprile) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-05-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-06-01'));

        $ordiniMaggio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleMaggio = array_sum($ordiniMaggio);
        $ordiniMaggio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingMaggio = array_sum($ordiniMaggio);
        $totaleMaggio = ($totaleMaggio - $shippingMaggio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-06-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-07-01'));

        $ordiniGiugno = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleGiugno = array_sum($ordiniGiugno);
        $ordiniGiugno = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingGiugno = array_sum($ordiniGiugno);
        $totaleGiugno = ($totaleGiugno - $shippingGiugno) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-07-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-08-01'));

        $ordiniLuglio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');

        $totaleLuglio = array_sum($ordiniLuglio);
        $ordiniLuglio = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingLuglio = array_sum($ordiniLuglio);
        $totaleLuglio = ($totaleLuglio - $shippingLuglio) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-08-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-09-01'));

        $ordiniAgosto = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleAgosto = array_sum($ordiniAgosto);
        $ordiniAgosto = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingAgosto = array_sum($ordiniAgosto);
        $totaleAgosto = ($totaleAgosto - $shippingAgosto) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-09-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-10-01'));

        $ordiniSettembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleSettembre = array_sum($ordiniSettembre);
        $ordiniSettembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingSettembre = array_sum($ordiniSettembre);
        $totaleSettembre = ($totaleSettembre - $shippingSettembre) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-10-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-11-01'));

        $ordiniOttobre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleOttobre = array_sum($ordiniOttobre);
        $ordiniOttobre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingOttobre = array_sum($ordiniOttobre);
        $totaleOttobre = ($totaleOttobre - $shippingOttobre) / 1.22;

        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-11-01'));
        $toDate = date('Y-m-d', strtotime($anno_ordini . '-12-01'));

        $ordiniNovembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleNovembre = array_sum($ordiniNovembre);
        $ordiniNovembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingNovembre = array_sum($ordiniNovembre);
        $totaleNovembre = ($totaleNovembre - $shippingNovembre) / 1.22;


        $fromDate = date('Y-m-d', strtotime($anno_ordini . '-12-01'));
        $toDate = date('Y-m-d', strtotime(($anno_ordini + 1) . '-12-01'));

        $ordiniDicembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('grand_total')
            ->getColumnValues('grand_total');
        $totaleDicembre = array_sum($ordiniDicembre);
        $ordiniDicembre = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('store_id', 3)
            ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
            ->addAttributeToFilter('status', array(
                array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE),
                array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING)
            ))
            ->addAttributeToSelect('shipping_amount')
            ->getColumnValues('shipping_amount');
        $shippingDicembre = array_sum($ordiniDicembre);
        $totaleDicembre = ($totaleDicembre - $shippingDicembre) / 1.22;


        $ordersITA = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 3);


        $ordersPortali = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 3)
            ->addFieldToFilter('customer_group_id', 4);


        $ordersECommerce = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('status', 'complete')
            ->addFieldToFilter('store_id', 3)
            ->addFieldToFilter('customer_group_id', array("neq"=>4));


        if (count($orders)>0) {
            $percOrdITA=(count($ordersITA)/count($orders))*100;
            $percOrdENG=0;

            $ordiniPortali=(count($ordersPortali)/count($orders))*100;
            $ordiniECommerce=(count($ordersECommerce))/count($orders)*100;
        }
        else {
            $percOrdITA=0;
            $percOrdENG=0;

            $ordiniPortali=0;
            $ordiniECommerce=0;

        }
    }
	

	
	
?>


<body class="tooltips">


		<div class="wrapper">

            <?php
				include("config/top.php");
            ?>			

            <?php
				include("config/left.php");
            ?>	

			
			<div class="page-content">
				<div class="container-fluid">
				
				
				<h1 class="page-heading">DASHBOARD<!--<small>Sub heading here</small>--></h1>
				
					 <!--<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					 <p><strong>Welcome!</strong></p>
					  <p class="text-muted">You probably here cause wanna know how is <a class="alert-link" href="#fakelink">Sentir UI kits template</a>, or you have purchased it. But whatever! I just wanna 
					  say thank you for viewing or purchasing my work. And let me know your feedback! <i class="fa fa-smile-o"></i></p>
					</div>-->
					
					<div class="row">
						<div class="col-sm-3">
							<div class="the-box no-border bg-success tiles-information">
								<i class="fa fa-users icon-bg"></i>
								<div class="tiles-inner text-center">
									<p>NUMERO PRODOTTI</p>
									<h1 class="bolded"><?php  echo count($collection) ?></h1> 
									<div class="progress no-rounded progress-xs">
									  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
									  </div>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="the-box no-border bg-primary tiles-information">
								<i class="fa fa-shopping-cart icon-bg"></i>
								<div class="tiles-inner text-center">
									<p>NUMERO ORDINI</p>
									<h1 class="bolded"><?php  echo count($orders) ?></h1> 
									<div class="progress no-rounded progress-xs">
									  <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
									  </div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="the-box no-border bg-danger tiles-information">
								<i class="fa fa-comments icon-bg"></i>
								<div class="tiles-inner text-center">
									<p>TOTALE ORDINI IMPONIBILI</p>
									<h1 class="bolded"><?php  echo number_format($lifetime,2,",",""). " €" ?></h1> 
									<div class="progress no-rounded progress-xs">
									  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
									  </div>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="the-box no-border bg-warning tiles-information">
								<i class="fa fa-money icon-bg"></i>
								<div class="tiles-inner text-center">
									<p>MEDIA ORDINI IMPONIBILI</p>
									<h1 class="bolded"><?php  echo number_format($average,2,",",""). " €" ?></h1> 
									<div class="progress no-rounded progress-xs">
									  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
									  </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
										
					
                    <div class="row">
                    	<div class="col-lg-12">
							<div class="the-box">
                                <h4 class="small-title" id="titolo_ordini_anno" style="float:left">ORDINI PER MESE ANNO <?php echo $anno_ordini ?></h4>
                                <div class="riga_anno">
                                    <label class="control-label" style="float:left" id="label_anno">Seleziona anno</label>
                                    <select name="anno" id="anno" class="form-control" onchange="attivaOrdiniAnno(this.value)">
                                        <?php
                                        $anno=2014;
                                        $anno_sistema=date("Y");
                                        for ($i=$anno; $i<=$anno_sistema; $i++){
                                            if ($i==$anno_sistema){
                                                echo "<option selected value=".$i.">".$i."</option>";
                                            }
                                            else {
                                                echo "<option value=".$i.">".$i."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="ordini_anno" style="height: 250px;margin-top:40px"></div>
							</div>
						</div>
                     </div> 
                     
                     
                     <div class="row">
						<div class="col-lg-4">
                                    <div class="the-box">
                                        <h4 class="small-title">PERCENTUALE ORDINI STORE ITA/INTERNATIONAL</h4>
                                        <div id="vendite-ecommerce" style="height: 270px;"></div>
                                    </div>	
						</div>
                        <div class="col-lg-4">
                                    <div class="the-box">
                                        <h4 class="small-title">PERCENTUALE ORDINI ECOMMERCE/PORTALI</h4>
                                        <div id="vendite-ebay-ecommerce" style="height: 270px;"></div>
                                    </div>
						</div>
						<div class="col-lg-4">

							<h4 class="small-title"><strong><i class="fa fa-users"></i> ULTIMI UTENTI ISCRITTI</strong></h4>
                            <?php
                            	$collection = Mage::getModel('customer/customer')
                            		->getCollection()
                            		->addAttributeToSelect('*')
                            		->setOrder('entity_id', "desc");
								
								$i=0;
								foreach ($collection as $customer) {
									$customer_id=$customer->getId();
									$customer = Mage::getModel('customer/customer')->load($customer_id);
									$nome = $customer->getFirstname();
									$cognome = $customer->getLastname();
									$gruppo=$customer->getGroupId();
									
									if ($gruppo==1){
										$descGruppo="E-Commerce";
									}
									else if ($gruppo==4){
										$descGruppo="Ebay";
									}
									
									echo "
									<div class=\"the-box no-border\">
										<div class=\"media user-card-sm\">
										  <a class=\"pull-left\" href=\"dettaglio-utente.php?id=$customer_id&dashboard=1\">
											<img class=\"media-object img-circle\" src=\"assets/img/avatar/avatar.jpg\" alt=\"Avatar\">
										  </a>
										  <div class=\"media-body\">
											<h4 class=\"media-heading\">".$nome." ".$cognome."</h4>
											<p class=\"text-success\">".$descGruppo."</p>
										  </div>
										 
										</div>
									</div>

									";
									$i=$i+1;
									if ($i==3){
										break;
									}
								}
							?>
							
							
							
						</div>
					</div>
				
				</div>

				<?php  include("config/footer.php") ?>
			
				
				
				</div>
				
			
		</div>

		<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/plugins/retina/retina.min.js"></script>
		<script src="assets/plugins/nicescroll/jquery.nicescroll.js"></script>
		<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>
 
		<!-- PLUGINS -->
		<script src="assets/plugins/slider/bootstrap-slider.js"></script>

		<!-- MORRIS JS -->
		<script src="assets/plugins/morris-chart/raphael.min.js"></script>
		<script src="assets/plugins/morris-chart/morris.min.js"></script>
		
		
		<!-- MAIN APPS JS -->
		<script src="assets/js/apps.js"></script>
        <script src="js/controlloform.js"></script>
        
        <script>
Morris.Bar({
  element: 'ordini_anno',
  data: [
  	{ y: ' <?php echo "Gen" ?>', a:  <?php echo number_format($totaleGennaio,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Feb" ?>', a:  <?php echo number_format($totaleFebbraio,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Mar" ?>', a:  <?php echo number_format($totaleMarzo,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Apr" ?>', a:  <?php echo number_format($totaleAprile,2,".","") ?>, b: 6000 },
    { y: ' <?php echo "Mag" ?>', a:  <?php echo number_format($totaleMaggio,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Giu" ?>', a:  <?php echo number_format($totaleGiugno,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Lug" ?>', a:  <?php echo number_format($totaleLuglio,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Ago" ?>', a:  <?php echo number_format($totaleAgosto,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Set" ?>', a:  <?php echo number_format($totaleSettembre,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Ott" ?>', a:  <?php echo number_format($totaleOttobre,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Nov" ?>', a:  <?php echo number_format($totaleNovembre,2,".","") ?>, b: 6000 },
	{ y: ' <?php echo "Dic" ?>', a:  <?php echo number_format($totaleDicembre,2,".","") ?>, b: 6000 }
  ],
  xkey: 'y',
  ykeys: ['a','b'],
  labels: ['Fatturato mensile','Media Obiettivo mensile'],
  barColors: ['#3BAFDA', '#E9573F']
});

Morris.Donut({
  element: 'vendite-ecommerce',
  data: [
    {label: "Ordini Store Italia", value:  <?php echo round($percOrdITA,2) ?>},
    {label: "Ordini Store Estero", value: <?php  echo round($percOrdENG,2) ?>}
  ],
  colors: ['#3BAFDA', '#8CC152'],
  formatter: function (x) { return x + "%"}
});

Morris.Donut({
  element: 'vendite-ebay-ecommerce',
  data: [
    {label: "Ordini E-commerce", value: <?php echo round($ordiniECommerce,2) ?>},
    {label: "Ordini Portali", value: <?php echo round($ordiniPortali,2) ?>}
  ],
  colors: ['#3BAFDA', '#8CC152'],
  formatter: function (x) { return x + "%"}
});
</script>
		
	</body>
<?php     
    }
else {
		echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');window.location='index.php'</script>";
}
?>
</html>