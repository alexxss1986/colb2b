<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$filename = "categoria";
$logFileName = $filename . '.log';


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$arrayCategorie = array("14", "33", "47", "82");
for ($i = 0; $i < count($arrayCategorie); $i++) {
    $categoriaPadre = Mage::getModel("catalog/category")->load($arrayCategorie[$i]);
    $subcats = $categoriaPadre->getChildren();

    foreach(explode(',',$subcats) as $subCatid) {
        $categoria = Mage::getModel('catalog/category')->load($subCatid);

        $query2="select id_ws from " . $resource->getTableName('wsca_group') . " where id_magento='".$categoriaPadre->getId()."'";
        $id_group = $readConnection->fetchOne($query2);


        $query="select id_ws from " . $resource->getTableName('wsca_subgroup') . " where id_macro_category='0000000002' and id_group='".$id_group."' and id_magento='".$categoria->getId()."'";
        $id_ws= $readConnection->fetchOne($query);

        Mage::log($id_ws);

        if ($id_ws==null || $id_ws==""){
            Mage::log($categoria->getName(),null,$logFileName);
        }
    }
}
