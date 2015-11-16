<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$array2=array();
$array1=array();
$k=0;
$y=0;
$stringQuery = "select value_id,value from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='82415'";
$immagine = $readConnection->fetchAll($stringQuery);
foreach ($immagine as $image) {
    $pos=strpos($image['value'],"_1.");
    if (!$pos){
        $array1[$k][0]=$image['value_id'];
        $array1[$k][1]=$image['value'];
        $k=$k+1;
    }
    else {
        $value=str_replace("_1.",".",$image['value']);
        echo $value."<br>";
        $array2[$y][0]=$image['value_id'];
        $array2[$y][1]=$value;
        $y=$y+1;
    }


}

for ($k=0; $k<count($array2); $k++){
    for ($y=0; $y<count($array1); $y++){
        if ($array2[$k][1]==$array1[$y][1]){
            $query = 'delete from ' . $resource->getTableName("catalog_product_entity_media_gallery") . ' where value_id="'.$array1[$y][0].'"';
            $writeConnection->query($query);
            unlink('../../media/catalog/product' . $array1[$y][1]);
            break;
        }
    }
}

