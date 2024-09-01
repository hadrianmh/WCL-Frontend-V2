<?php

if(!empty($_POST['token']) AND !empty($_POST['code'])){
	
	session_start();

	if($_SESSION['digit'] == $_POST['code']){
		require 'connect.php';

		$token = mysqli_real_escape_string($connect, $_POST['token']);

		$query = "SELECT email FROM user WHERE md5(email) = '$token' AND status='0' LIMIT 1";

		$sql = $connect->query($query);

		if($sql->num_rows > 0){
			$query_update = "UPDATE user SET status='1' WHERE md5(email) = '$token'";
			$sql_update = $connect->query($query_update);
			if($sql_update){
				echo "<div class='alert alert-success'>Verification success.</div>";
				echo "<div class='alert alert-warning'><strong><a href='signin.php'>Click here to sign in!</a></strong></div>";
			} else {
				echo "<div class='alert alert-danger'>Verification failed, please contact the administrator.</div>";
			}

		} else{
			echo "<div class='alert alert-danger'>Token invalid.</div>";
		}
		
	} else {
		echo "<div class='alert alert-danger'>Wrong security code.</div>";
	}

} else {
	echo "<div class='alert alert-danger'>Token can't empty invalid.</div>";
}
?>