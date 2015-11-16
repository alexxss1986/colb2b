<?php
class TreC_RemoveImages_Model_Observer
{
    public function remove()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');


        $filename = "catalogo";
        $logFileName = $filename . '.log';



        try {
            $dataCorrente = date('Y-m-d');

            $stringQuery = "select product_number,running,finish from " . $resource->getTableName('wsca_removeimg_log') . " where dataImport='" . $dataCorrente . "'";
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

                Mage::log("INIZIO REMOVE",null,$logFileName);


                if ($product_number == 1) {
                    // salvo la pagina in cui sono arrivato
                    $query = "insert into " . $resource->getTableName('wsca_removeimg_log') . " (product_number,running,finish,dataImport) values('" . $product_number . "','1','" . $finish . "','" . $dataCorrente . "')";
                    $writeConnection->query($query);
                } else {
                    // salvo la pagina in cui sono arrivato
                    $query = "update " . $resource->getTableName('wsca_removeimg_log') . " set product_number='" . $product_number . "',running='1',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                    $writeConnection->query($query);
                }


                // recupero tutti i prodotti configurabili
                $collection = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToFilter('type_id', 'configurable')
                    ->addAttributeToFilter('entity_id', array('gteq' => $product_number));

                $count_prodotti = 0;
                foreach ($collection as $product) {

                    Mage::log($product->getId(), null, $logFileName);

                    $stringQuery = "select value,value_id from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='".$product->getId()."'";
                    $gallery = $readConnection->fetchAll($stringQuery);


                    foreach ($gallery as $row) {
                        $valore = $row["value"];
                        $value_id = $row["value_id"];
                        $filename = "/home/coltortiboutique/public_html/media/catalog/product" . $valore;
                        if (file_exists($filename)) {

                        } else {
                            Mage::log("ELIMINATO ".$product->getId(), null, $logFileName);
                            $query = "delete from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where value_id='" . $value_id . "'";
                            $writeConnection->query($query);
                        }
                    }


                    $count_prodotti = $count_prodotti + 1;


                    if ($count_prodotti == 2000) {
                        if ($count_prodotti == count($collection)) {
                            $finish = 1;
                        } else {
                            $finish = 0;
                        }


                        // salvo la pagina in cui sono arrivato
                        $query = "update " . $resource->getTableName('wsca_removeimg_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                        $writeConnection->query($query);

                        $readConnection->closeConnection();
                        $writeConnection->closeConnection();

                        break;
                    } else if ($count_prodotti == count($collection)) {
                        $finish = 1;

                        // salvo la pagina in cui sono arrivato
                        $query = "update " . $resource->getTableName('wsca_removeimg_log') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                        $writeConnection->query($query);
                        

                        $readConnection->closeConnection();
                        $writeConnection->closeConnection();

                    }
                }

                Mage::log("FINE REMOVE",null,$logFileName);

            }
        } catch (Exception $e){

        }
    }
}