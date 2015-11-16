<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if (isset($_REQUEST['sku'])){
        $sku=$_REQUEST['sku'];

        include("percorsoMage.php");
        require_once "../".$MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $id_prodotto=Mage::getModel('catalog/product')->getIdBySku($sku);
        if ($id_prodotto!=""){
            $product = Mage::getModel('catalog/product')->load($id_prodotto);
            $website=$product->getWebsiteIds();
            $flag=false;
            for ($i=0; $i<count($website); $i++){
                if ($website[$i]!=2){
                    $flag=true;
                    break;
                }
            }
            if ($flag==false) {
                $type_id = $product->getTypeId();
                if ($type_id == "simple") {
                    $nome = $product->getName();
                    $sku = $product->getSku();
                    $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);

                    if ($stock != "") {
                        $qta = $stock->getQty();
                    } else {
                        $qta = 0;
                    }

                    $array[0][0] = "1";
                    $array[0][1] = $nome;
                    $array[0][2] = $sku;
                    $array[0][3] = number_format($qta, 0);
                    $array[0][4] = $product->getId();
                } else if ($type_id == "configurable") {
                    $k = 0;
                    $ids = $product->getTypeInstance()->getUsedProductIds();
                    foreach ($ids as $id) {
                        $productSimple = Mage::getModel('catalog/product')->load($id);
                        $sku = $productSimple->getSku();
                        $nome = $productSimple->getName();
                        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productSimple);

                        if ($stock != "") {
                            $qta = $stock->getQty();
                        } else {
                            $qta = 0;
                        }

                        $array[$k][0] = "2";
                        $array[$k][1] = $nome;
                        $array[$k][2] = $sku;
                        $array[$k][3] = number_format($qta, 0);
                        $array[$k][4] = $productSimple->getId();

                        $k = $k + 1;
                    }
                }
            }
            else {
                $array[0][0]="0";
                $array[0][1]="Errore. Id errato";
            }
        }
        else {

            $array[0][0] = "0";
            $array[0][1] = "Errore. Id errato";

        }
        echo json_encode($array);
    }
    else {
        echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}
?>