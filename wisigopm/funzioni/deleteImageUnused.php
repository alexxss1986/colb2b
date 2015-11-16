<?php

/*

If your products have been deleted but not your media gallery images you
can use the following SQL statement to remove these dormant records.

Please make sure you backup your installation and database before running this
SQL statement. It has been used on Mage 1.8.1 system:

DELETE `img` FROM `catalog_product_entity_media_gallery` img
LEFT JOIN `catalog_product_entity` AS prod ON img.entity_id = prod.entity_id
WHERE prod.sku IS NULL

 */

try {

    require_once '../../app/Mage.php';
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



    $sImgDir = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product';

    $oResource = Mage::getSingleton('core/resource');
    $sMediaTbl = $oResource->getTableName('catalog_product_entity_media_gallery');
    $oReadConn = $oResource->getConnection('core_read');

    $i=0;
    $oIterator = new RecursiveDirectoryIterator($sImgDir);
    foreach( new RecursiveIteratorIterator($oIterator) as $sFile) {

        if(strpos($sFile, "/cache") !== false || is_dir($sFile) ) {
            continue;
        }

        $sFilePath      = str_replace($sImgDir, "", $sFile);
        $sQuery         = 'SELECT value FROM ' . $sMediaTbl . ' WHERE value="' . $sFilePath . '"';
        $sValue         = $oReadConn->fetchOne($sQuery);

        if($sValue == false){
            echo $cleanfile . "## REMOVEING: " . $sFilePath . " ## <br>";
            unlink($sFile);
            $i++;
        }
    }

    echo "\r\n\r\n Finished removing $i  un-used product images\r\n\r\n";

} catch (Exception $e) {
    echo '<pre style="color:red;">' . $e->getMessage() . '</pre>';
}