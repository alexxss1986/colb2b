<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('ca_brand', 975);

$filename="eliminazioneImmagine";
$logFileName= $filename.'.log';

$filename2="prodottiConImmagine";
$logFileName2= $filename2.'.log';

foreach ($collection as $product) {
    $prodottoConfigurabile = Mage::getModel('catalog/product')->load($product->getId());
    Mage::log("".$prodottoConfigurabile->getId(),null,$logFileName);
        $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
        $items = $mediaApi->items($prodottoConfigurabile->getId());
        $attributes = $prodottoConfigurabile->getTypeInstance()->getSetAttributes();
        $gallery = $attributes['media_gallery'];

        if (count($items)>0){
            Mage::log("".$prodottoConfigurabile->getId(),null,$logFileName2);
        }

        foreach($items as $item){
            if ($gallery->getBackend()->getImage($prodottoConfigurabile, $item['file'])) {
                $gallery->getBackend()->removeImage($prodottoConfigurabile, $item['file']);
            }
        }
        $prodottoConfigurabile->save();

}
Mage::log("FINE",null,$logFileName);

?>