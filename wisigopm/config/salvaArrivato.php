<?php
$id_p=$_REQUEST['id_p'];
$ordine=$_REQUEST['ordine'];
$valore=$_REQUEST['valore'];
$id=$_REQUEST['id'];
$boutique=$_REQUEST['boutique'];

include("connect.php");
$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");
mysql_query("SET NAMES 'utf8' ");

$query=mysql_query("insert into coordini_arrivato (id_ordine, id_prodotto, arrivato,id_boutique) values ('".$ordine."','".$id_p."','".$valore."','".$boutique."')");

if ($query){
    $array[0][0]=$id;
    echo json_encode($array);
}
else {
    $array[0][0]=false;
    echo json_encode($array);
}