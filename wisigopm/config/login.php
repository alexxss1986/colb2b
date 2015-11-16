<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Login Script</title>
    <link rel="stylesheet" type="text/css" href="css/Stile.css">
  </head>
  
  <body>


<?php
include("percorsoMage.php");
require_once "../".$MAGE;
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
if (isset($_REQUEST['username']) && isset ($_REQUEST['password'])){
$username=$_REQUEST['username'];
$password=$_REQUEST['password'];


    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');

    $stringQuery = "select livello,codBoutique,magazzino,nome from " . $resource->getTableName('utente_wisigopm') . " where username='" . $username . "' and password='".md5($password)."'";


    $result = $readConnection->fetchAll($stringQuery);

if(count($result)==1) {

    foreach ($result as $row) {

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['livello'] = $row["livello"];
        $_SESSION['codBoutique'] = $row["codBoutique"];
        $_SESSION['magazzino'] =$row["magazzino"];
        $_SESSION['nome'] =$row["nome"];


        if ($_SESSION['livello']!=1 && $_SESSION['livello']!=4) {
            echo "<script type=\"text/javascript\">location.replace(\"../dashboard.php\");</script>";
        }
        else if ($_SESSION['livello']==4) {
            echo "<script type=\"text/javascript\">location.replace(\"../utenti.php\");</script>";
        }
        else {
                echo "<script type=\"text/javascript\">location.replace(\"../ordini.php\");</script>";
        }

    }
	
	
}


else {
	if ($username=="" || $password==""){
		echo"<script type=\"text/javascript\">alert(\"Username o password non inseriti.\"); location.replace(\"../index.php\");</script>";
	}
	else {
		echo"<script type=\"text/javascript\">alert(\"Username o Password errati.\"); location.replace(\"../index.php\");</script>";
	}
}

?>
<noscript>Questo documento contiene codice Javascript che il tuo browser non &egrave; in grado di visualizzare</noscript>
<?php
}
else {
			echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
}
?>
</body>
</html>
