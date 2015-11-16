<?php


require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


// aggiornamento immagini
$product = Mage::getModel('catalog/product');
$product->setStoreId(3)->load($product->getIdBySku("152001ABS000028-0RO"));
$attributes = $product->getTypeInstance(true)->getSetAttributes($product);
$gallery = $attributes['media_gallery'];
$images = $product->getMediaGalleryImages();
foreach ($images as $image) {
    $path = $image->getUrl();
    $file = basename($path);

    $punto = strrpos($file, ".");
    $file_new = substr($file, 0, $punto);

    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
    $numero_img = substr($file_new, $posizione + 1, strlen($file_new) - $posizione);


    $carattere = substr($numero_img, 1, 1);
    if ($carattere == "s" || $carattere == "t") {
        $numero_img = substr($numero_img, 0, 2);
    } else {
        $numero_img = substr($numero_img, 0, 1);
    }


    if ($numero_img == "3") {
        $backend = $gallery->getBackend();
        $backend->updateImage(
            $product,
            $image->getFile(),
            array('disabled' => 0, 'position' => "1")
        );

        $product->getResource()->saveAttribute($product, 'media_gallery');
        $product->save();
    } else if ($numero_img == "3s" || $numero_img == "3t") {
        $backend = $gallery->getBackend();
        $backend->updateImage(
            $product,
            $image->getFile(),
            array('label' => '', 'exclude' => 1, 'position' => 1)
        );

        $product->getResource()->saveAttribute($product, 'media_gallery');
        $product->save();
    } else if ($numero_img == "4s") {
        $backend = $gallery->getBackend();
        $backend->updateImage(
            $product,
            $image->getFile(),
            array('label' => 'back', 'position' => 2)
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