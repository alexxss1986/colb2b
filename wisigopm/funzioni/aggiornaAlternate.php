<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$stringQuery = "select url_key,page_id from " . $resource->getTableName('splash_page');
$brand = $readConnection->fetchAll($stringQuery);
foreach ($brand as $row) {
    $id=$row["page_id"];
    $url_key=$row["url_key"];
    $layout='<reference name="head">
                <block type="core/template" name="alternateHead" template="alternate/link_alternate.phtml">
                                    <action method="setData"><name>link_it</name><value>'.$url_key.'.html</value></action>
                                    <action method="setData"><name>link_en</name><value>'.$url_key.'.html</value></action>
                                    <action method="setData"><name>link_en_us</name><value>'.$url_key.'.html</value></action>
                </block>
            </reference>';


    $query = "update " . $resource->getTableName('splash_page') . " set layout_update_xml='" . $layout . "' where page_id='" . $id . "'";
    $writeConnection->query($query);
}