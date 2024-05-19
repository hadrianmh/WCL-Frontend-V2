<?php
error_reporting(0);
date_default_timezone_set("Asia/jakarta");

$host = 'host.docker.internal';
$user = 'root';
$pass = 'root';
$dbase = 'apps_v3';

$connect = new mysqli($host,$user,$pass,$dbase);
var_dump($connect);

if($connect->connect_error){
	die('<div class="alert alert-danger">Connection failed: server offline.</div>');
}
?>