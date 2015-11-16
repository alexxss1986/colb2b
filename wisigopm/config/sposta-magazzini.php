<?php
session_cache_limiter('nocache');
session_start();

include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');


if (isset($_SESSION['username'])) {
    if ($_SESSION['livello'] == 0) {

        $magazzinoArray = array();


        $id_ordine=$_REQUEST['id_ordine'];

        $ordine=Mage::getModel('sales/order')->loadByIncrementId($id_ordine);

        $warehouses = Mage::getModel('wgmulti/warehouse')
            ->getCollection()
            ->addOrder('position', 'ASC');
        $warehouseData = $warehouses->toFlatArray();

        $i=0;
        foreach ($warehouseData as $wid => $wdata) {
           $magazzinoArray[$i] = $wid;
            $i=$i+1;
        }


        // controllo quantita
        $_items = $ordine->getAllItems();

        $indice=0;
        $flag=true;
        foreach ($_items as $item) {

            $somma=0;
            if ($item->getProductType()=="simple") {

                $orderedQty = $item->getQtyOrdered();
                for ($i = 0; $i < count($magazzinoArray); $i++) {
                    $nome = "qty_" . $magazzinoArray[$i];
                    $qty = $_REQUEST[$nome];
                    $somma=$somma+$qty[$indice];
                }
                $indice = $indice + 1;

                if ($somma>$orderedQty){
                    $flag=false;
                    $prodottoErrore[]=$item->getSku();
                }

            }

        }

        if ($flag==false){
            $errore="Errrore!! Sono state specificate quantità superiori per i seguenti prodotti:\\n\\n";
            for ($i=0; $i<count($prodottoErrore); $i++){
                $errore.=$prodottoErrore[$i]."\\n";
            }


            ?>
            <script type='text/javascript'>alert('<?php echo $errore ?>'); location.replace('../dettaglio-ordine-magazzino.php?id=<?php echo $id_ordine ?>');</script>";

            <?php
        }
        else {
            $_items = $ordine->getAllItems();

            $indice=0;
            foreach ($_items as $item) {

                $additionalData = array();

                if ($item->getProductType()=="simple") {

                    for ($i = 0; $i < count($magazzinoArray); $i++) {
                        $nome = "qty_" . $magazzinoArray[$i];
                        $qty = $_REQUEST[$nome];
                        if ($qty[$indice] > 0) {
                            $additionalData[$magazzinoArray[$i]] = $qty[$indice];
                        }
                    }
                    $indice = $indice + 1;

                    $item->setAdditionalData(serialize($additionalData))->save();

                }

            }
        }





        echo "<script type='text/javascript'>alert('Spostamento effettuato con successo!'); location.replace('../ordini-magazzino.php');</script>";
    }
    else {
        echo "<script type='text/javascript'>alert('Non è possibile visualizzare questa pagina!'); location.replace('../index.php');</script>";
    }

}
else {
    echo "<script type='text/javascript'>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!'); location.replace('../index.php');</script>";
}