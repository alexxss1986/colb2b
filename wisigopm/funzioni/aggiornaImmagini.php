<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$filename="immagini";
$logFileName= $filename.'.log';



$collection = Mage::getModel('catalog/product')
    ->getCollection()
    ->addAttributeToFilter('type_id','configurable');

Mage::log("INIZIO",null,$logFileName);
Mage::log(count($collection), null, $logFileName);
foreach ($collection as $product3) {
    $product = Mage::getModel('catalog/product')->load($product3->getId());
        try {
            Mage::log($product->getId(), null, $logFileName);
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
                    $backend = $gallery->getBackend();
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
        }
        catch (Exception $e){
            Mage::log("ID ".$id_prodotti[$i]." ERRORE: ".$e->getMessage());
        }



}
Mage::log("FINE",null,$logFileName);


