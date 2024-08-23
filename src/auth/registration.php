<?php

if(!empty($_POST['name']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['role']) AND !empty($_POST['code'])){

	session_start();
	
	if($_SESSION['digit'] == $_POST['code']){

		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$role = $_POST['role'];

		require '../utils/api.php';
		$apiClient = new HttpRequest();
		$data = [
			"name"		=> $name,
			"email"		=> $email,
			"password"	=> $password,
			"role"		=> (int) $role
		];
		$request = $apiClient->post('/register', $data);

		echo json_encode($request['response']);

	} else {
		echo '{"code":400,"response":{"message":"Wrong security code."},"status":"error"}';
	}

} else {
	echo '{"code":400,"response":{"message":"Not allowed."},"status":"error"}';
}
?>