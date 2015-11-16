<?php
include("config/connect.php");
$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");

if (isset($_REQUEST['email'])) {
    $email = $_REQUEST['email'];

    $controllo=mysql_query("select * from waemail where email='".$email."'");
    if (mysql_num_rows($controllo)==0) {

        $query = mysql_query("insert into waemail(email) values('" . $email . "')");
        if ($query) {
            echo "<script type='text/javascript'>alert('Email salvata con successo!');location.replace('http://www.waimealifestyle.com');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Errore!');location.replace('http://www.waimealifestyle.com');</script>";
        }

    }
    else {
        echo "<script type='text/javascript'>alert('Errore! Email già presente');location.replace('http://www.waimealifestyle.com');</script>";
    }
}


