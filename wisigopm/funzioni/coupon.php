<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$dataOggi=date('dmY');
$newdate = date('Y-m-d', strtotime('+10 day', strtotime(date('Y-m-d'))));

$newdate="2015-08-30";

$rulesCollection = Mage::getModel('salesrule/rule')
    ->getCollection()
    ->addFieldToFilter('name', array('like' => 'Coupon%'))
    ->addFieldToFilter('to_date', array('eq' => $newdate));


foreach($rulesCollection as $rule) {
   $coupon = $rule->getName();
    $coupon=str_replace("Coupon di sconto per ","",$coupon);
    echo $coupon;
}