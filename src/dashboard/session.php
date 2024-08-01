<?php
require '../utils/jwt.php';
session_start();

if(!isset($_COOKIE['access_token']))
{
	if(!isset($_COOKIE['refresh_token']))
	{
		header("Location:../signin.php");
	
	} else {
		require '../utils/api.php';
		$apiClient = new HttpRequest();
		$data = [
			"action"		=> "refresh",
			"email"			=> $_SESSION['email'],
			"refresh_token"	=> $_COOKIE['refresh_token']
		];
		$request = $apiClient->post('/auth', $data);
		
		if($request['status'] !== 500 && empty($request['response']->response->message)) {
			setcookie('access_token', $request['response']->response->data->access_token, time() + (59 * 60), "/");
			setcookie('refresh_token', $request['response']->response->data->refresh_token, time() + (1380 * 60), "/");
			
			$decode_jwt = decode_jwt($request['response']->response->data->access_token);
			$_SESSION['id'] 		= $decode_jwt['uniqid'];
			$_SESSION['name'] 		= $decode_jwt['name'];
			$_SESSION['email'] 		= $decode_jwt['username'];
			$_SESSION['role'] 		= $decode_jwt['role'];
			$_SESSION['status'] 	= $decode_jwt['status'];
			$_SESSION['account']	= $decode_jwt['account'];
			$_SESSION['picture'] 	= $decode_jwt['picture'];

		} else {
			setcookie('access_token', '', time() + (59 * 60), "/");
			setcookie('refresh_token', '', time() + (1380 * 60), "/");
			header("Location:../signin.php");
		}
	}

} else {
	$decode_jwt = decode_jwt($_COOKIE['access_token']);
	$_SESSION['id'] 		= $decode_jwt['uniqid'];
	$_SESSION['name'] 		= $decode_jwt['name'];
	$_SESSION['email'] 		= $decode_jwt['username'];
	$_SESSION['role'] 		= $decode_jwt['role'];
	$_SESSION['status'] 	= $decode_jwt['status'];
	$_SESSION['account']	= $decode_jwt['account'];
	$_SESSION['picture'] 	= $decode_jwt['picture'];
}
?>