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
            if ($id_prodotto==""){
                $query=mysql_query("select * from gssku_etichetta where sku_etichetta='$sku'");
                if (mysql_num_rows($query)==1){
                    $array[0][0]="0";
                    $array[0][1]="Errore. Lo sku inserito è relativo al codice del singolo prodotto. Inserire lo sku del produttore.";
                }
                else {
                    $array[0][0]="0";
                    $array[0][1]="Errore. Codice sku errato";
                }
            }
            else {
                $type_id=$prodotto->getTypeId();

                if ($type_id=="configurable"){
                    //creo un array con un record che è il prodotto configurabile
                    $t=0;

                    $nome=$prodotto->getName();
                    $sku=$prodotto->getSku();

                    $array[$t][0]=$id_prodotto;
                    $array[$t][1]=$nome;
                    $array[$t][2]=$sku;
                }
                else {
                    $array[0][0]="0";
                    $array[0][1]="Errore. Lo sku inserito è relativo al codice del singolo prodotto. Inserire lo sku del produttore.";

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