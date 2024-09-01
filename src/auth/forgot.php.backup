<?php

date_default_timezone_set("Asia/jakarta");

if(!empty($_POST['email']) AND !empty($_POST['code'])){
	
	session_start();
	
	if($_SESSION['digit'] == $_POST['code']){
		require 'connect.php';
		require 'sendForgot.php';

		$email = mysqli_real_escape_string($connect, $_POST['email']);

		$query = "SELECT * FROM user WHERE email = '$email'";
		$sql = $connect->query($query);

		if($sql->num_rows > 0){

			$row = $sql->fetch_array(MYSQLI_ASSOC);

			$data_id = $row['id'];
			$data_email = $row['email'];
			$data_name = $row['name'];
			$date_create_token = date('Y-m-d H:i');
			$date_expired_token = date('Y-m-d H:i', strtotime('+600 seconds', strtotime($date_create_token)));
			$token = md5(sha1($data_id.$date_create_token));

			$query_insert = "INSERT INTO forgot_password (token,iduser,created,expired) VALUES ('$token','$data_id','$date_create_token','$date_expired_token')";
			$connect->query($query_insert);

			$forgot = new forgot_password();
			$forgot->send_forgot($token,$data_name,$data_email);

		} else {
			echo "<div class='alert alert-danger'>Email isn't registered.</div>";
		}
		
	} else {
		echo "<div class='alert alert-danger'>Wrong security code.</div>";
	}

} else {
	echo "<div class='alert alert-danger'>Not allowed.</div>";
}
?>