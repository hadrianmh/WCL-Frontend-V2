<?php
class verify_email {

	function send_verification($token,$email,$nama){
		require 'phpmailer/PHPMailer.php';
		require 'phpmailer/Exception.php';
		require 'phpmailer/SMTP.php';

		$mail = new PHPMailer\PHPMailer\PHPMailer();
		try {
		    
		    require 'phpmailer/Config.php';
		    
		    $mail->addAddress($email, $nama);

		    //Content
		    $mail->isHTML(true);
		    $mail->Subject = 'E-mail Verification';
		    $mail->Body    = "
		    <html>
				<head></head>
				<body>
					<p>Hi, ".$nama."</p>
					<p>This e-mail was sent you to verify your e-mail address. Please copy and paste token to continue the submission:</p>
					<p> Token is: <b>".$token."</b></p>
					<br/>
					<p>Regards,</p>
					<p>CV. Wisnu Cahaya Label</p>
					<p>https://www.wisnucahayalabel.com</p>
				</body>
			</html>";
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    echo "<div class='alert alert-success'>Registration success. In order to complete your registration, please copy and paste <strong>token</strong> in the email that we have sent to you in the inbox or junk folder.</div>";
		    echo "<div class='alert alert-warning'><strong><a href='verify.php?token=".$token."'>Click here to activation!</a></strong></div>";

		} catch (Exception $e) {
			require 'connect.php';
			$query_delete = "DELETE FROM user WHERE email='$email'";
			$sql_delete = $connect->query($query_delete);
			if($sql_delete){
				echo "<div class='alert alert-danger'>Registration failed, please contact the administrator.</div>";
			}
		}
	}
}

?>