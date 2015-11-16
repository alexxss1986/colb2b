<?php

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$i=0;
$data[$i][0]="Id Ordine";
$data[$i][1]="Sku Prodotto";
$data[$i][2]="Nome";
$data[$i][3]="Scalare";
$data[$i][4]="Stagione";
$data[$i][5]="Genere";
$data[$i][6]="Gruppo";
$data[$i][7]="Descrizione Gruppo";
$data[$i][8]="Sottogruppo";
$data[$i][9]="Descrizione Sottogruppo";
$data[$i][10]="Brand";
$data[$i][11]="Descrizione Brand";
$data[$i][12]="Listino";
$data[$i][13]="Sconto";

$i=$i+1;

$orders = Mage::getModel('sales/order')->getCollection()
    ->addFieldToFilter(
        array(
            'status',
            'status'
        ),
        array(
            array('eq' => 'complete'),
            array('eq' => 'processing')
        )
    )
    ->setOrder('increment_id', 'desc');



foreach ($orders as $ordine) {
    // recuero dati ordine
    $id_ordine = $ordine->getIncrementId();

    $_items = $ordine->getAllVisibleItems();

    $k = 1;
    foreach ($_items as $item) {
        $id_p = $item->getId();
        $sku = $item->getSku();
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

        if ($product->getTypeId() == "simple") {
            $nome = $item->getName();
            $id_product = $product->getId();
            $scalare=$product->getData("ca_scalare");
            $stagioneDB=$product->getAttributeText("ca_stagione");
            $annoDB=$product->getAttributeText("ca_anno");
            $prezzo=number_format($product->getPrice()*1.22,2,",","");


            $iva = $item->getTaxPercent();
            $prezzoI=$item->getPrice()+($item->getPrice()*$iva)/100;
            if ($iva==0) {
                $prezzoI=$item->getPrice();
            }
            else {
                $prezzoI=$item->getPrice()+($item->getPrice()*$iva)/100;
            }

            $sconto = number_format(($item->getDiscountAmount() * 100) / ($prezzoI), 0);
            if ($sconto==0){
                $sconto="";
            }
            else {
                $sconto=$sconto. "%";
            }

            // stagione
            $stringQuery = "select id_ws from " . $resource->getTableName('wsca_season') . " where LOWER(STAGIONE)='" . $stagioneDB . "' and LOWER(ANNO)='" . $annoDB . "'";
            $stagioneWS = $readConnection->fetchOne($stringQuery);

            // genere
            $categories=$product->getCategoryIds();
            $sesso=$categories[1];
            $categoria=Mage::getModel("catalog/category")->load($sesso);
            $genere=$categoria->getName();

            // gruppo e descrizione
            $gruppo=$categories[2];
            $categoria=Mage::getModel("catalog/category")->load($gruppo);
            $descrizioneGruppoWS=$categoria->getName();
            $stringQuery = "select id_ws from " . $resource->getTableName('wsca_group') . " where id_magento='" . $gruppo . "'";
            $gruppoWS = $readConnection->fetchOne($stringQuery);


            // sottogruppo e descrizione
            $sottogruppo=$categories[3];
            $categoria=Mage::getModel("catalog/category")->load($sottogruppo);
            $descrizioneSottoGruppoWS=$categoria->getName();
            $stringQuery = "select id_ws from " . $resource->getTableName('wsca_subgroup') . " where id_magento='" . $sottogruppo . "'";
            $sottogruppoWS = $readConnection->fetchOne($stringQuery);

            //brand e descrizione
            $brand=$product->getData("ca_brand");
            $descrizioneBrandWS=$product->getAttributeText("ca_brand");
            $stringQuery = "select id_ws from " . $resource->getTableName('wsca_brand') . " where id_magento='" . $brand . "'";
            $brandWS = $readConnection->fetchOne($stringQuery);

            $data[$i][0]=$id_ordine;
            $data[$i][1]=$sku;
            $data[$i][2]=$nome;
            $data[$i][3]=$scalare;
            $data[$i][4]=$stagioneWS;
            $data[$i][5]=$genere;
            $data[$i][6]=$gruppoWS;
            $data[$i][7]=$descrizioneGruppoWS;
            $data[$i][8]=$sottogruppoWS;
            $data[$i][9]=$descrizioneSottoGruppoWS;
            $data[$i][10]=$brandWS;
            $data[$i][11]=$descrizioneBrandWS;
            $data[$i][12]=$prezzo;
            $data[$i][13]=$sconto;
            $i=$i+1;

        }
    }

}


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=report.csv');

$fp = fopen("php://output", 'w');
foreach ($data as $dati) {
    fputcsv($fp, $dati,";");
}
fclose($fp);


