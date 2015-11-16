<?php
session_cache_limiter('nocache');
session_start();

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$filename = "geo";
$logFileName = $filename . '.log';

if (isset($_SESSION['username'])) {
    if ($_SESSION['livello'] == 0 && $_SESSION['username'] == "coltorti") {
        if (isset($_REQUEST['brand']) && isset($_REQUEST['stagione']) && isset($_REQUEST['anno']) && isset($_REQUEST['markup']) && isset($_REQUEST['store'])) {
            $store=$_REQUEST['store'];
            $brand=$_REQUEST['brand'];
            $stagione=$_REQUEST['stagione'];
            $anno=$_REQUEST['anno'];
            $markup=$_REQUEST['markup'];

            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToFilter('ca_brand', $brand)
                ->addAttributeToFilter('ca_stagione', $stagione);


            foreach ($collection as $product) {
                Mage::log($product->getId(),null,$logFileName);
                $product2 = Mage::getModel('catalog/product')->setStoreId($store)->load($product->getId());
                $prezzo=$product2->getPrice()+(($product2->getPrice()*$markup)/100);
                $product2->setPrice($prezzo);
                $product2->save();

            }


            $stringQuery = "select id from " . $resource->getTableName('wisigopm_markup') . " where store='" . $store . "' and brand='" . $brand . "' and stagione='" . $stagione . "' anno='" . $anno . "' markup='" . $markup . "'";
            $idmarkUp = $readConnection->fetchOne($stringQuery);

            if ($idmarkUp == null) {
                $query = "insert into " . $resource->getTableName('wisigopm_markup') . " (store,brand,stagione,anno,markup) values('" . $store . "','" . $brand . "','" . $stagione . "','" . $anno . "','" . $markup . "')";
                $writeConnection->query($query);
            }
            else {
                $query = "update " . $resource->getTableName('wisigopm_markup') . " set store='" . $store . "',brand='" . $brand . "',stagione='" . $stagione . "',anno='" . $anno . "',markup='" . $markup . "' where id='".$idmarkUp."'";
                $writeConnection->query($query);
            }


            echo "<script type='text/javascript'>alert('Operazione effettuata con successo!'); location.replace('../index.php');</script>";

        }
        else {
            echo "<script type='text/javascript'>alert('Errore! Alcuni parametri non sono stati specificati'); location.replace('../inserisci-geopricing.php');</script>";
        }
    }
    else {
        echo "<script type='text/javascript'>alert('Non è possibile visualizzare questa pagina!'); location.replace('../index.php');</script>";
    }
}
else {
    echo "<script type='text/javascript'>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!'); location.replace('../index.php');</script>";
}