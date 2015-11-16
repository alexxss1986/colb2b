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


            // controllo se il prodotto esiste
            if ($stock!=""){
                // se esiste recupero la quantità e la disponibilità
                $qtaProdotto=$stock->getQty();
                $isStock=$stock->getIsInStock();
                $qtaTot=$qtaProdotto-$qta; // calcolo la qta rimanente
                if ($qtaTot==0){
                    $isStock=0; // se la qta rimanente è 0 allora setto il prodotto come non disponibile
                }
                if ($qtaTot>=0){
                    // se la qta rimanente è >= 0 aggiorno la qta e la disponibilità del prodotto
                    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->getId());
                    $stockItem->setData('qty', $qtaTot);
                    $stockItem->setData('is_in_stock', $isStock);
                     $stockItem->save();

                    // se la quantità rimasta è uguale a zero, controllo se anche tutti gli altri prodotti semplici sono a zero; in questo caso setto il prodotto configurabile a non disponibile (serve per non far visualizzare il prodotto nel catalogo)
                    if ($qtaTot==0){
                        $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable') ->getParentIdsByChild($idProdotto);
                        $productConfig = Mage::getModel('catalog/product')->load($parentIds[0]);
                        $ids = $productConfig->getTypeInstance()->getUsedProductIds();
                        $flag=false;
                        foreach ( $ids as $id ) {
                            $productSimple = Mage::getModel('catalog/product')->load($id);
                            $qty=(int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($productSimple)->getQty();
                            if ($qty!=0){
                                $flag=true;
                                break;
                            }
                        }

                        if ($flag==false){
                            $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productConfig->getId());
                            $stockItem->setData('qty', 0);
                            $stockItem->setData('is_in_stock', 0);
                            $stockItem->save();

                        }
                    }


                    echo "<script>alert('Operazione eseguita con successo.');location.replace('../index.php')</script>";

                }
                else {
                    // se la qta rimanente è minore di 0 significa che ho inserito una quantita venduta maggiore di quella presente in magazzino
                    echo "<script>alert('La quantità inserita non è presente in magazzino.');location.replace('../scala_disponibilita.php')</script>";
                }
            }
            else {

                // sku inserito è sbagliato
                echo "<script>alert('Errore. Codice sku errato.');location.replace('../scala_disponibilita.php')</script>";
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