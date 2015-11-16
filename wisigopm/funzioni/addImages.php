<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $directory="../../var/images";

$filename="immagini";
$logFileName= $filename.'.log';

        $k=0;

        $arrayFile=array();
        if ($handle = opendir($directory)) {
            while (false !== ($file = readdir($handle)))
            {
                if (($file != ".")
                    && ($file != ".."))
                {
                    $arrayFile[]=$file;
                }
            }
            closedir($handle);
        }

        sort($arrayFile);

        $id_prodotti=array();

Mage::log("INIZIO",null,$logFileName);
$sku_etichetta_backup="";
        foreach($arrayFile as $file)
        {

            try {
                // recupero il nome del file senza estensione
                $punto = strrpos($file, ".");
                $file_new = substr($file, 0, $punto);

                // recupero il codice etichetta e il numero dell'immagine
                $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza

                $sku_etichetta = substr($file_new, 0, $posizione);
                $numero_img = substr($file_new, $posizione + 1, 1);

                $prodottoConfigurabile = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_etichetta);

                if ($prodottoConfigurabile) {
                    if ($sku_etichetta_backup != $sku_etichetta) {
                        $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                        $items = $mediaApi->items($prodottoConfigurabile->getId());
                        foreach ($items as $item) {
                            $mediaApi->remove($prodottoConfigurabile->getId(), $item['file']);
                            unlink('../../media/catalog/product' . $item['file']);
                        }
                        $prodottoConfigurabile->save();

                        $prodottoConfigurabile = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_etichetta);
                    }

                    // aggiungo l'immagine al prodotto: se è la prima immagine la inserisco sia come immagine in lista, sia come thumbnail che come immagine principale; altrimenti la inserisco solo come immagine in lista
                    if ($prodottoConfigurabile->getSmallImage() != null && $prodottoConfigurabile->getSmallImage() != "no_selection" && $prodottoConfigurabile->getImage() != null && $prodottoConfigurabile->getImage() != "no_selection" && $prodottoConfigurabile->getThumbnail() != null && $prodottoConfigurabile->getThumbnail() != "no_selection") {
                        $prodottoConfigurabile->addImageToMediaGallery("../../var/images/" . $file, array(""), false, false);
                    } else {
                        if ($numero_img == "3") {
                            $prodottoConfigurabile->addImageToMediaGallery("../../var/images/" . $file, array('image', 'small_image', 'thumbnail'), false, false);
                        } else {
                            $prodottoConfigurabile->addImageToMediaGallery("../../var/images/" . $file, array(""), false, false);
                        }
                    }

                    $prodottoConfigurabile->save();
                    


                    if ($sku_etichetta_backup != $sku_etichetta) {
                        $id_prodotti[$k] = $prodottoConfigurabile->getId();
                        $k = $k + 1;
                        $sku_etichetta_backup = $sku_etichetta;
                    }


                    $delete = unlink("../../var/images/" . $file);
                }
            }
            catch (Exception $e){
                Mage::log("SKU ".$sku_etichetta." ERRORE: ".$e->getMessage());
            }
        }


Mage::log("FINE1",null,$logFileName);
        if (isset($id_prodotti)){
            for ($i=0; $i<count($id_prodotti); $i++){
                try {
                    Mage::log($id_prodotti[$i], null, $logFileName);
                    $product = Mage::getModel('catalog/product')->load($id_prodotti[$i]);
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
                    Mage::log("ID ".$id_prodotti[$i]." ERRORE: ".$e);
                }


            }
        }
Mage::log("FINE2",null,$logFileName);


