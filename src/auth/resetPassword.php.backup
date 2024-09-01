<?php

date_default_timezone_set("Asia/jakarta");

if(!empty($_POST['token']) AND !empty($_POST['password'])){
	
	session_start();
	
	if($_SESSION['digit'] == $_POST['code']){
		require 'connect.php';

		$token = mysqli_real_escape_string($connect,$_POST['token']);
		$password = mysqli_real_escape_string($connect,md5($_POST['password']));

		$query = "SELECT * FROM forgot_password WHERE token='$token'";
		$sql = $connect->query($query);

		if($sql->num_rows > 0){

			$row = $sql->fetch_array(MYSQLI_ASSOC);
			$iduser = $row['iduser'];
			$tokenExpired = $row['expired'];
			$timenow = date('Y-m-d H:i:s');

			if($timenow < $tokenExpired){

				$query_update = "UPDATE user SET password='$password' WHERE id='$iduser'";
				$sql_update = $connect->query($query_update);

				if($sql_update){
					$query_delete1 = "DELETE FROM forgot_password WHERE token='$token'";
					$connect->query($query_delete1);
					echo "<div class='alert alert-success'>Reset password success.</div>";
			    	echo "<div class='alert alert-warning'><strong><a href='signin.php'>Click here to sign in!</a></strong></div>";

				} else {
					echo "<div class='alert alert-danger'>Something went wrong, please try again.</div>";
				}

			} else {
				$query_delete2 = "DELETE FROM forgot_password WHERE token='$token'";
				$connect->query($query_delete2);
				echo "<div class='alert alert-danger'>Token expired.</div>";
			}

		} else {
			echo "<div class='alert alert-danger'>Token invalid.</div>";
		}
		
	} else {
		echo "<div class='alert alert-danger'>Wrong security code.</div>";
	}

} else {
	echo "<div class='alert alert-danger'>Not allowed.</div>";
}
?>