<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']!=2) {
        if (isset($_REQUEST['valore'])){
            $valore=$_REQUEST['valore'];

            include("percorsoMage.php");
            require_once "../".$MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            include("connect.php");
            $conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
            mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");

            $array=array();

            $anno_ordini=$valore;

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-01-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-02-01'));


            $ordiniGennaio = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleGennaio = array_sum($ordiniGennaio);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-02-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-03-01'));

            $ordiniFebbraio = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleFebbraio = array_sum($ordiniFebbraio);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-03-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-04-01'));

            $ordiniMarzo = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleMarzo = array_sum($ordiniMarzo);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-04-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-05-01'));

            $ordiniAprile = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleAprile = array_sum($ordiniAprile);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-05-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-06-01'));

            $ordiniMaggio = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleMaggio = array_sum($ordiniMaggio);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-06-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-07-01'));

            $ordiniGiugno = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleGiugno = array_sum($ordiniGiugno);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-07-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-08-01'));

            $ordiniLuglio = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleLuglio = array_sum($ordiniLuglio);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-08-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-09-01'));

            $ordiniAgosto = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleAgosto = array_sum($ordiniAgosto);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-09-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-10-01'));

            $ordiniSettembre = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleSettembre = array_sum($ordiniSettembre);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-10-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-11-01'));

            $ordiniOttobre = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleOttobre = array_sum($ordiniOttobre);

            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-11-01'));
            $toDate = date('Y-m-d', strtotime($anno_ordini.'-12-01'));

            $ordiniNovembre = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleNovembre = array_sum($ordiniNovembre);


            $fromDate = date('Y-m-d', strtotime($anno_ordini.'-12-01'));
            $toDate = date('Y-m-d', strtotime(($anno_ordini+1).'-12-01'));

            $ordiniDicembre = Mage::getModel('sales/order')->getCollection()
                ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
                ->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
                ->addAttributeToSelect('grand_total')
                ->getColumnValues('grand_total');
            $totaleDicembre = array_sum($ordiniDicembre);

            $array[0][0]=number_format($totaleGennaio,2,".","");
            $array[0][1]=number_format($totaleFebbraio,2,".","");
            $array[0][2]=number_format($totaleMarzo,2,".","");
            $array[0][3]=number_format($totaleAprile,2,".","");
            $array[0][4]=number_format($totaleMaggio,2,".","");
            $array[0][5]=number_format($totaleGiugno,2,".","");
            $array[0][6]=number_format($totaleLuglio,2,".","");
            $array[0][7]=number_format($totaleAgosto,2,".","");
            $array[0][8]=number_format($totaleSettembre,2,".","");
            $array[0][9]=number_format($totaleOttobre,2,".","");
            $array[0][10]=number_format($totaleNovembre,2,".","");
            $array[0][11]=number_format($totaleDicembre,2,".","");
            $array[0][12]=$valore;

            mysql_close($conn);

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