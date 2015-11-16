<?php

class TreC_SetMetaProduct_Model_Observer
{
    public function getLastCategory($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->load($_categories[0]);

        return $_category;
    }

    public function meta()
    {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $stringQuery = "select product_number,running,finish from " . $resource->getTableName('wsca_copy_catalogo');
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

            if ($product_number == 1) {
                // salvo la pagina in cui sono arrivato
                $query = "insert into " . $resource->getTableName('wsca_copy_catalogo') . " (product_number,running,finish) values('" . $product_number . "','1','" . $finish . "')";
                $writeConnection->query($query);
            } else {
                // salvo la pagina in cui sono arrivato
                $query = "update " . $resource->getTableName('wsca_copy_catalogo') . " set product_number='" . $product_number . "',running='1',finish='" . $finish . "'";
                $writeConnection->query($query);
            }

            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('type_id', 'configurable')
                ->addAttributeToFilter('entity_id',  array('gteq' => $product_number));

            $filename = "setMeta";
            $logFileName = $filename . '.log';

            $count_prodotti=0;

            Mage::log("INIZIO", null, $logFileName);

            foreach ($collection as $product) {
                Mage::log($product->getId(), null, $logFileName);
                $product2 = Mage::getModel('catalog/product')->load($product->getId());
                $nome_brand=$product2->getAttributeText("ca_brand");
                $nome_colore=$product2->getAttributeText("ca_colore");
                $nome_stagione=$product2->getAttributeText("ca_stagione");
                $sku=$product2->getSku();



                if ($nome_colore=="Colori misti"){
                    $nome_colore=$product2->getData("ca_codice_colore_fornitore");
                }

                $category=$this->getLastCategory($product2);
                $nome_sottocategoria=$category->getName();

                $parent = $category->getParentId();
                while ($parent != "2") {
                    $id_categoria = $parent;
                    $category = Mage::getModel('catalog/category')->load($parent);
                    $parent = $category->getParentId();
                }

                $category = Mage::getModel('catalog/category')->load($id_categoria);
                $nome_categoria = $category->getName();



                // meta prodotto configurablile

                   /* $numero = rand(0, 2);
                    if ($numero == 0) {
                        $stringa = "Acquista";
                    }
                    if ($numero == 1) {
                        $stringa = "Compra";
                    }
                    if ($numero == 2) {
                        $stringa = "Shop";
                    }*/

                    $title = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da " . ucwords(strtolower($nome_categoria)) . " " . ucwords(strtolower($nome_colore));

                    /*$description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";

                    $keyword1 = $title;
                    $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da " . ucwords(strtolower($nome_categoria));
                    $keyword3 = "Shop online " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria)) . " da " . ucwords(strtolower($nome_categoria));

                    $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;*/

               /* $product2->setMetaKeyword($keywords);
                $product2->getResource()->saveAttribute($product2, 'meta_keyword');
                $product2->setMetaDescription(ucfirst(strtolower($description)));
                $product2->getResource()->saveAttribute($product2, 'meta_description');*/
                $product2->setMetaTitle(ucfirst(strtolower($title)));
                $product2->getResource()->saveAttribute($product2, 'meta_title');


                $count_prodotti = $count_prodotti + 1;

                if ($count_prodotti == 1000) {
                    if ($count_prodotti == count($collection)) {
                        $finish = 1;
                    } else {
                        $finish = 0;
                    }


                    // salvo la pagina in cui sono arrivato
                    $query = "update " . $resource->getTableName('wsca_copy_catalogo') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "'";
                    $writeConnection->query($query);


                    break;
                }
                else if ($count_prodotti == count($collection)){
                    $finish = 1;

                    // salvo la pagina in cui sono arrivato
                    $query = "update " . $resource->getTableName('wsca_copy_catalogo') . " set product_number='" . $product->getId() . "',running='0',finish='" . $finish . "'";
                    $writeConnection->query($query);


                    $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                    foreach ($pCollection as $process) {
                        $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                    }


                    Mage::app()->getCacheInstance()->flush();


                }

            }


            Mage::log("FINE", null, $logFileName);
        }
    }

}