<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['qta']) && isset($_SESSION['taglie_scelte']) && isset($_SESSION['scalari_scelti'])){
            // recupero le quantità e i prezzi dei prodotti inseriti

            $qta=$_REQUEST['qta'];


            // azzero le variabili di sessioni
            unset($_SESSION['qta']);
            unset($_SESSION['taglie_s']);
            unset($_SESSION['scalari_s']);

            // assegno le variabili di session per la qta,prezzo,taglie e colori inseriti per tenerli in memoria al salvataggio del prodotto
            $_SESSION['qta']=$qta;
            $_SESSION['taglie_s']=$_SESSION['taglie_scelte'];
            $_SESSION['scalari_s']=$_SESSION['scalari_scelti'];


            unset($_SESSION['taglie_scelte']);
            unset($_SESSION['scalari_scelti']);

            echo "<script>alert('Operazione eseguita con successo');</script>";
            echo "<script>self.close();</script>";

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