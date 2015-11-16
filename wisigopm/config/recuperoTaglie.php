<?php
session_cache_limiter('nocache');
session_start();

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

require('sorter.php');

if ($_SESSION['username']){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['valore']) && isset($_REQUEST['indice'])){
            $valore=$_REQUEST['valore'];
            $indice=$_REQUEST['indice'];

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');

            $stringQuery = "select id_taglia,taglia from " . $resource->getTableName('scalariusa_taglie') . " where id_scalare='".$valore."'";
            $listaTaglie = $readConnection->fetchAll($stringQuery);
            $i=0;
            foreach ($listaTaglie as $row) {
                $array[$i][0] = $row['id_taglia'];
                $array[$i][1] = $row['taglia'];
                $array[$i][2]=$indice;
                $i=$i+1;
            }

            // ordinamento
            $oSorter = new ArraySorter();
            $oSorter->setArray($array);
            $array=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);


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