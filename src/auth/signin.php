<?php
if(!empty($_POST['email']) AND !empty($_POST['password']) AND !empty($_POST['code']))
{
	session_start();
	if((empty($_SESSION['digit']) ? '' : $_SESSION['digit']) == $_POST['code'])
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

?>