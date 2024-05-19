<?php

if(!empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['code'])){

	session_start();

	// if($_SESSION['digit'] == $_POST['code']){

		require 'connect.php';
		include 'history.php';
  var_dump($_POST);

		$email = mysqli_real_escape_string($connect, $_POST['email']);
		$pass = mysqli_real_escape_string($connect, md5($_POST['password']));

		$query = "SELECT id,name,email,role,status,account FROM user WHERE email='$email' LIMIT 1";
		$sql = $connect->query($query);

		if($sql->num_rows > 0){

			$row = $sql->fetch_array();
			$data_name = $row['name'];
			$data_email = $row['email'];
			$data_role = $row['role'];
			$data_status = $row['status'];
			$data_account = $row['account'];
			$data_id = $row['id'];

			if($data_status > 0){

				$query_cekPass = "SELECT password FROM user WHERE email='$email' AND password='$pass'";
				$sql_cekPass = $connect->query($query_cekPass);

				if($sql_cekPass->num_rows > 0){

					$_SESSION['name'] = $data_name;
					$_SESSION['email'] = $data_email;
					$_SESSION['role'] = $data_role;
					$_SESSION['status'] = $data_status;
					$_SESSION['account'] = $data_account;
					$_SESSION['signin'] = 'is_signin';
					$_SESSION['id'] = $data_id;
					//$_SESSION['timeout'] = time();

					$agent = $_SERVER['HTTP_USER_AGENT'];
					$url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
					$ip = $_SERVER['REMOTE_ADDR'];
					
					$log = logger($connect,'Sign in', 'URL: '.$url.' - Browser: '.$agent.' - IP: '.$ip);

					if($data_account == 0){
						echo "<script>document.location.href='dashboard/index.php?page=profile';</script>";
					} else {
						echo "<script>document.location.href='dashboard/index.php?page=dashboard';</script>";
					}

				} else {
					echo "<div class='alert alert-danger'>Wrong password.</div>";
				}

			} else {
				echo "<div class='alert alert-danger'>Please verify email to continue or contact the administrator.</div>";
			}

		} else {
			echo "<div class='alert alert-danger'>Email isn't registered.</div>";
		}
	
	// } else {
	// 	echo "<div class='alert alert-danger'>Wrong security code.</div>";
	// }

} else {
	echo "<div class='alert alert-danger'>Not allowed.</div>";
}
?>