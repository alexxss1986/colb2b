<?php
session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_SESSION['qta'])){
            $array[0][0]="1";
        }
        else {
            $array[0][0]="0";
        }

        echo json_encode($array);
    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}
?>