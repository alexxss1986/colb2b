<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['sku'])){
            $sku=$_REQUEST['sku'];

            include("percorsoMage.php");
            require_once "../".$MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


            $id_prodotto=Mage::getModel('catalog/product')->getIdBySku($sku);
            $prodotto = Mage::getModel('catalog/product')->load($id_prodotto);

            if ($id_prodotto=="") {

                    $array[0][0]="0";
                    $array[0][1]="Errore. Codice sku errato";

            }
            else {
                // se lo sku esiste su magento allora recupero l'id e il tipo prodotto
                $type_id=$prodotto->getTypeId();

                if ($type_id=="configurable"){
                    // se il tipo è configurabile creo un array con come primo record il prodotto configurabile e gli altri record saranno i prodotti semplici associati
                    $t=0;

                    $nome=$prodotto->getName();
                    $sku=$prodotto->getSku();

                    $array[$t][0]=$id_prodotto;
                    $array[$t][1]=$nome;
                    $array[$t][2]=$sku;
                    $array[$t][3]=$type_id;

                }
                else if ($type_id=="simple"){
                    // se il prodotto è un semplice allora l'array sarà costituito da un solo elemento
                    $array[0][0]="0";
                    $array[0][1]="Errore. Codice sku errato";
                }

            }


            echo json_encode($array);
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