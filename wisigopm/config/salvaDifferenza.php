<?php
$spedizione=$_REQUEST['spedizione'];
$id=$_REQUEST['id'];
$tnt=$_REQUEST['tnt'];
$differenza=$_REQUEST['differenza'];
$i=$_REQUEST['i'];

include("connect.php");
$conn=mysql_connect($HOST, $USER, $PASSWORD)or die("Connessione fallita");
mysql_select_db($DB, $conn)or die("Impossibile selezionare il DB");

$spedizione=str_replace(",",".",$spedizione);
$tnt=str_replace(",",".",$tnt);
$differenza=str_replace(",",".",$differenza);


$query=mysql_query("insert into waordini_spedizioni (id_ordine, spedizione, tnt, differenza) values ('".$id."','".$spedizione."','".$tnt."','".$differenza."')");

if ($query){
	$array[0][0]=true;
	$array[0][1]=$i;
	echo json_encode($array);
}
else {
	$array[0][0]=false;
	$array[0][1]=$i;
	echo json_encode($array);
	
}