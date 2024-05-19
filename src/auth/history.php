<?php
require '../dashboard/session.php';
require 'connect.php';

function logger($connect,$queryLog,$data){
	$query = "INSERT INTO log (data,query,date,user) VALUES ('$data','$queryLog','".date('Y-m-d h:i:s')."','".$_SESSION['name']."')";
	$sql = $connect->query($query);
	return $sql;
}
?>
