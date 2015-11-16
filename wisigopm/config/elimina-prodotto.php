<?php

session_cache_limiter('nocache');
session_start();

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


if (isset($_SESSION['username'])){
    if ($_SESSION['livello']=="3"){
        if (isset($_REQUEST['check'])){
            $flag=true;
            $array=$_REQUEST['check'];

            foreach ($array as $value){
                // se il prodotto è configurabile elimino anche tutti i prodotti semplici associati
                // se il prodotto è semplice verifico se il configurabile associato a altri prodotti semplici; se non ha più associazioni, viene eliminato
                $prodotto = Mage::getModel('catalog/product')->load($value);
                if ($prodotto!=""){
                    $sku=$prodotto->getSku();
                    $type_id=$prodotto->getTypeId();
                    if ($type_id=="simple"){
                        $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($value);


                            $k=0;

                            $prodotto->delete();

                            $prodottoConfig = Mage::getModel('catalog/product')->load($parentIds);
                            $ids = $prodottoConfig->getTypeInstance()->getUsedProductIds();
                            foreach ( $ids as $id ) {
                                $k=$k+1;
                            }


                            if ($k==0){
                                $prodottoConfig->delete();
                            }


                    }
                    else if ($type_id=="configurable"){
                        $ids = $prodotto->getTypeInstance()->getUsedProductIds();
                        $flag2=true;
                        foreach ( $ids as $id ) {
                            $prodottoSimpleAss = Mage::getModel('catalog/product')->load($id);
                            $skuAss=$prodottoSimpleAss->getSku();
                            $prodottoSimpleAss->delete();

                            $flag2=true;
                        }

                        if ($flag2){
                            $prodotto->delete();
                        }
                        else {
                            $flag=false;
                        }
                    }
                }
                else {
                    $flag=false;
                }
            }

            if ($flag==true){
                echo "<script type=\"text/javascript\">alert(\"Eliminazione effettuata con successo.\"); location.replace(\"../index.php\");</script>";

            }
            else {

                echo "<script type=\"text/javascript\">alert(\"Errore. Eliminazione non riuscita.\"); location.replace(\"../elimina-prodotto.php\");</script>";

            }

        }
        else {
            echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
        }

    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}

?>