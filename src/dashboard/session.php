<?php

if(!isset($_SESSION)){
	session_start();
	if(!empty($_SESSION['signin']) == 'is_signin'){

		/*$inactive = 60;

		$session_life = time() - $_SESSION['timeout'];

		if($session_life > $inactive){
			session_destroy();
			header("Location:../signin.html");
		}

		$_SESSION['timeout'] = time();
		*/

	} else {
		header("Location:../signin.php");
	}
}

?>