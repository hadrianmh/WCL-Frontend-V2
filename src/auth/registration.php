<?php

if(!empty($_POST['nama']) AND !empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['role']) AND !empty($_POST['code'])){

	session_start();
	
	if($_SESSION['digit'] == $_POST['code']){
		require 'connect.php';
		require 'sendToken.php';

		$nama = mysqli_real_escape_string($connect, $_POST['nama']);
		$email = mysqli_real_escape_string($connect, $_POST['email']);
		$password = mysqli_real_escape_string($connect, md5($_POST['password']));
		$role = mysqli_real_escape_string($connect, $_POST['role']);

		$query = "SELECT email FROM user WHERE email='$email'";

		$sql = $connect->query($query);

		if($sql->num_rows < 1){
			$query = "INSERT INTO user (name,email,password,role,status,account) VALUES ('$nama', '$email', '$password', '$role', '0', '0')";
			$sql = $connect->query($query);
			if($sql){
				$token = md5($email);
				$verify = new verify_email();
				$verify->send_verification($token,$email,$nama);

			} else {
				echo "<div class='alert alert-danger'>Registration failed, please try again.</div>";
			}

		} else {
			echo "<div class='alert alert-danger'>Email registered.</div>";
		}

	} else {
		echo "<div class='alert alert-danger'>Wrong security code.</div>";
	}

} else {
	echo "<div class='alert alert-danger'>Not allowed.</div>";
}
?>