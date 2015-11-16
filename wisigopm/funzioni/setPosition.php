<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable')
    ->addAttributeToFilter('entity_id', array('gt' => 26110));;

$filename="prodotti";
$logFileName= $filename.'.log';

foreach ($collection as $product2) {
    try {
        Mage::log($product2->getId(), null, $logFileName);
        $product = Mage::getModel('catalog/product')->load($product2->getId());
        $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
        $gallery = $attributes['media_gallery'];
        $images = $product->getMediaGalleryImages();
        foreach ($images as $image) {
            $path = $image->getUrl();
            $file = basename($path);

            $punto = strrpos($file, ".");
            $file_new = substr($file, 0, $punto);

            $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
            $numero_img = substr($file_new, strlen($file_new) - 1, 1);


            if ($numero_img == "3") {
                $backend->updateImage(
                    $product,
                    $image->getFile(),
                    array('disabled' => 0, 'position' => "1")
                );

                $product->getResource()->saveAttribute($product, 'media_gallery');
                $product->save();
            } else {
                    $backend = $gallery->getBackend();
                    $backend->updateImage(
                        $product,
                        $image->getFile(),
                        array('disabled' => 0, 'position' => ($numero_img - 2))
                    );

                    $product->getResource()->saveAttribute($product, 'media_gallery');
                    $product->save();

            }

        }
    } catch (Exception $e) {
        Mage::log("ID " . $product2->getId() . " ERRORE: " . $e);
    }

}