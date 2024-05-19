<?php

session_start();

if(!empty($_SESSION['signin']) == 'is_signin'){
	require 'connect.php';
	include 'history.php';
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$log = logger($connect,'Sign out', 'URL: '.$url.' - Browser: '.$agent.' - IP: '.$ip);
	session_destroy();
	header("Location:../signin.php");
	exit;

} else {
	header("Location:../signin.php");
}

?>