<?php

$configfile = '../config.json';
if (file_exists($configfile)) {
	$getconfig = file_get_contents($configfile);

	$ENV = json_decode($getconfig, TRUE);
	if($ENV !== null && $ENV['base_url_api'] !== null && $ENV['base_path_api'] !== null && $ENV["base_port_api"] !== null && $ENV["base_url"] !== null && $ENV["base_port"] !== null)
	{
		// Define env to set cookie
		setcookie('base_url', $ENV["base_url"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_port', $ENV["base_port"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_url_api', $ENV["base_url_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_path_api', $ENV["base_path_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_port_api', $ENV["base_port_api"], time() + (10 * 365 * 24 * 60 * 60), "/");
		setcookie('base_dashboard_api', $ENV["base_dashboard_api"], time() + (10 * 365 * 24 * 60 * 60), "/");

		if(!empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['code']))
		{
			session_start();
			if($_SESSION['digit'] == $_POST['code'])
			{
				$email = $_POST['email'];
				$pass = $_POST['password'];
				
				require '../utils/api.php';
				$apiClient = new HttpRequest();
				$data = [
					"action"	=> "login",
					"email"		=> $email,
					"password"	=> $pass
				];
				$request = $apiClient->post('/auth', $data);
				if($request['status'] !== 500 && empty($request['response']->response->message)) {
					setcookie('refresh_token', $request['response']->response->data->refresh_token, time() + (1380 * 60), "/");
					setcookie('access_token', $request['response']->response->data->access_token, time() + (59 * 60), "/");
					echo "<script>document.location.href='dashboard/index.php?page=dashboard';</script>";
				} else {
					echo "<div class='alert alert-danger'>".($request['status'] !== 500 ? $request['response']->response->message : $request['response'])."</div>";
				}
			
			} else {
				echo "<div class='alert alert-danger'>Wrong security code.</div>";
			}

		} else {
			echo "<div class='alert alert-danger'>Not allowed.</div>";
		}

	} else {
		echo "<div class='alert alert-danger'>Config is not configured well.</div>";
	}

} else {
	echo "<div class='alert alert-danger'>Config file not found.</div>";
}

?>