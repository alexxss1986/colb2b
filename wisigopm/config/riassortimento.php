<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if (isset($_REQUEST['check'])){

        include("percorsoMage.php");
        require_once "../".$MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


        $check=$_REQUEST['check'];

        for ($i=0; $i<count($check); $i++){
            $idProdotto=$check[$i];
            $nome_text="qta_".$idProdotto;
            $qta=$_REQUEST[$nome_text];


            $product = Mage::getModel('catalog/product')->load($idProdotto);

            $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);

            if ($stock!=""){
                // se esiste recupero la quantità e la disponibilità
                $qtaProdotto=$stock->getQty();
                $qtaTot=$qtaProdotto+$qta; // calcolo la qta rimanente

                // aggiorno la qta del prodotto
                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
                $stockItem->setData('qty',$qtaTot);

                //recupero l'id del prodotto configurabile e lo carico
                $idProdottoConfigurabile = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($product->getId());

                if ($idProdottoConfigurabile!="") {
                    $prodottoConfigurabile = Mage::getModel('catalog/product')->load($idProdottoConfigurabile);
                    // controllo se la qta è > 0 e il prodotto configurabile associato ha delle immagini
                    if ($qtaTot>0 && $prodottoConfigurabile->getSmallImage()!=null && $prodottoConfigurabile->getSmallImage()!="no_selection" && $prodottoConfigurabile->getThumbnail()!=null && $prodottoConfigurabile->getThumbnail()!="no_selection" && $prodottoConfigurabile->getImage()!=null && $prodottoConfigurabile->getImage()!="no_selection"){
                        $stockItem->setData('is_in_stock', 1);
                    }
                    else {
                        $stockItem->setData('is_in_stock', 0);
                    }
                }
                else {
                    if ($qtaTot>0){
                        $stockItem->setData('is_in_stock', 1);
                    }
                    else {
                        $stockItem->setData('is_in_stock', 0);
                    }
                }
                
                $stockItem->save();

                echo "<script>alert('Operazione eseguita con successo.');location.replace('../index.php')</script>";


            }
            else {
                // sku inserito è sbagliato
                echo "<script>alert('Errore. Codice sku errato.');location.replace('../riassortimento.php')</script>";
            }

        }
    }
    else {
        echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}
?>
