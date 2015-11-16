<?php
session_cache_limiter('nocache');
session_start();
if ($_SESSION['username']){
    if ($_SESSION['livello']==3) {
        if (isset($_SESSION['taglia']) && isset($_SESSION['scalare'])){
            $taglia=$_SESSION['taglia'];
            $scalare=$_SESSION['scalare'];

            $array[0][0]=$taglia;
            $array[0][1]=$scalare;
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