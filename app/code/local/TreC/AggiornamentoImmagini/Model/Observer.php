<?php
class TreC_AggiornamentoImmagini_Model_Observer
{
    public function update()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $filename = "update";
        $logFileName = $filename . '.log';
        
        $dataCorrente = date('Y-m-d');

        $stringQuery = "select product_number,running,finish from " . $resource->getTableName('wsca_img_log') . " where dataImport='" . $dataCorrente . "'";
        $importLog = $readConnection->fetchAll($stringQuery);
        $product_number = 0;
        $finish = 0;
        $running = 0;
        foreach ($importLog as $row) {
            $product_number = $row['product_number'];
            $finish = $row['finish'];
            $running = $row['running'];
        }

        $product_number=$product_number+1;

        if ($product_number != "" && $running == 0 && $finish == 0) {

            Mage::log("INIZIO UPDATE", null, $logFileName);


            if ($product_number == 1) {
                // salvo la pagina in cui sono arrivato
                $query = "insert into " . $resource->getTableName('wsca_img_log') . " (product_number,running,finish,dataImport) values('" . $product_number . "','1','" . $finish . "','" . $dataCorrente . "')";
                $writeConnection->query($query);
            } else {
                // salvo la pagina in cui sono arrivato
                $query = "update " . $resource->getTableName('wsca_img_log') . " set product_number='" . $product_number . "',running='1',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                $writeConnection->query($query);
            }


            // recupero tutti i prodotti configurabili
            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('type_id', 'configurable')
                ->addAttributeToFilter('visibility', 4)
                ->addAttributeToFilter('entity_id', array('gteq' => $product_number));

            $count_prodotti = 0;
            foreach ($collection as $product) {
                Mage::log($product->getId(),null,$logFileName);
                $productEng = Mage::getModel('catalog/product')->setStoreId(2)->load($product->getId());
                $attributes = $productEng->getTypeInstance(true)->getSetAttributes($productEng);
                $gallery = $attributes['media_gallery'];
                $images = $productEng->getMediaGalleryImages();
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
                            $productEng,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => "1")
                        );

                        $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                        $productEng->save();
                    } else if ($numero_img == "3s" || $numero_img == "3t") {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productEng,
                            $image->getFile(),
                            array('label' => '', 'exclude' => 1, 'position' => 1)
                        );

                        $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                        $productEng->save();
                    } else if ($numero_img == "4s") {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productEng,
                            $image->getFile(),
                            array('label' => 'back', 'position' => 2)
                        );

                        $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                        $productEng->save();
                    } else {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productEng,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => ($numero_img - 2))
                        );

                        $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                        $productEng->save();
                    }

                }


                $productUsa = Mage::getModel('catalog/product')->setStoreId(3)->load($product->getId());
                $attributes = $productUsa->getTypeInstance(true)->getSetAttributes($productUsa);
                $gallery = $attributes['media_gallery'];
                $images = $productUsa->getMediaGalleryImages();
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
                            $productUsa,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => "1")
                        );

                        $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                        $productUsa->save();
                    } else if ($numero_img == "3s" || $numero_img == "3t") {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productUsa,
                            $image->getFile(),
                            array('label' => '', 'exclude' => 1, 'position' => 1)
                        );

                        $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                        $productUsa->save();
                    } else if ($numero_img == "4s") {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productUsa,
                            $image->getFile(),
                            array('label' => 'back', 'position' => 2)
                        );

                        $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                        $productUsa->save();
                    } else {
                        $backend = $gallery->getBackend();
                        $backend->updateImage(
                            $productUsa,
                            $image->getFile(),
                            array('disabled' => 0, 'position' => ($numero_img - 2))
                        );

                        $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                        $productUsa->save();
                    }

                }


                $count_prodotti = $count_prodotti + 1;


                if ($count_prodotti == 500) {
                    if ($count_prodotti == count($collection)) {
                        $finish = 1;
                    } else {
                        $finish = 0;
                    }


                    // salvo la pagina in cui sono arrivato
                    $query = "update " . $resource->getTableName('wsca_img_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                    $writeConnection->query($query);

                    $readConnection->closeConnection();
                    $writeConnection->closeConnection();

                    break;
                } else if ($count_prodotti == count($collection)) {
                    $finish = 1;

                    // salvo la pagina in cui sono arrivato
                    $query = "update " . $resource->getTableName('wsca_img_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                    $writeConnection->query($query);

                    $readConnection->closeConnection();
                    $writeConnection->closeConnection();

                }
            }

            Mage::log("FINE UPDATE",null,$logFileName);
        }
    }
}