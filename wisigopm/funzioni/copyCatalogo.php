<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id', 'simple')
    ->addAttributeToFilter('entity_id', array(
        'from' => 11158,
        'to' => 20000
    ));

$filename = "copy";
$logFileName = $filename . '.log';


Mage::log("INIZIO",null,$logFileName);
$pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
foreach ($pCollection as $process) {
    $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
}
foreach ($collection as $product) {
    Mage::log($product->getId(),null,$logFileName);
    $product2 = Mage::getModel('catalog/product')->load($product->getId());
    $product2->setWebsiteIds(array(1,2,3));
    $product2->save();

}

/*$pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
foreach ($pCollection as $process) {
    $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
}
*/

Mage::log("FINE",null,$logFileName);