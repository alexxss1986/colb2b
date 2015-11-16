<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==0 && $_SESSION['username']=='coltorti') {
        if (isset($_REQUEST['store']) && isset($_REQUEST['brand']) && isset($_REQUEST['stagione']) && isset($_REQUEST['anno'])){
            $store=$_REQUEST['store'];
            $brand=$_REQUEST['brand'];
            $stagione=$_REQUEST['stagione'];
            $anno=$_REQUEST['anno'];

            include("percorsoMage.php");
            require_once "../".$MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $writeConnection = $resource->getConnection('core_write');

            $stringQuery = "select id,store,brand,stagione,anno,markup from " . $resource->getTableName('wisigopm_markup') . " ";

            if ($store!="" || $brand!="" || $stagione!="" || $anno!=""){
                $stringQuery .= "where ";
            }


            if ($store!=""){
                $stringQuery .= "store='".$store."' and ";

            }

            if ($brand!=""){
                $stringQuery .= "brand='".$brand."' and ";
            }

            if ($stagione!=""){
                $stringQuery .= "stagione='".$stagione."' and ";
            }

            if ($anno!=""){
                $stringQuery .= "anno='".$anno."' and ";
            }


            if ($store!="" || $brand!="" || $stagione!="" || $anno!=""){
                $stringQuery = substr($stringQuery, 0, strlen($stringQuery) - 5);
            }


            $geo = $readConnection->fetchAll($stringQuery);


            $i=0;

            foreach ($geo as $row) {
                $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_brand");
                if ($attr->usesSource()) {
                    $brandMage = $attr->getSource()->getOptionText($row["brand"]);
                }

                $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_stagione");
                if ($attr->usesSource()) {
                    $stagioneMage = $attr->getSource()->getOptionText($row["stagione"]);
                }


                $store=Mage::getModel('core/store_group')->load($row["store"]);
                $storeMage=$store->getName();



                $array[$i][0]=$row["id"];
                $array[$i][1]=$storeMage;
                $array[$i][2]=$brandMage;
                $array[$i][3]=$stagioneMage;
                $array[$i][4]=$row["anno"];
                $array[$i][5]=$row["markup"];

                $i=$i+1;
            }


            if ($i==0){
                $array[0][0]="0";
                $array[0][1]="Errore. Non ci sono geopricing!";
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