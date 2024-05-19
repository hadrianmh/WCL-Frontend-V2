<?php
class forgot_password {

	function send_forgot($token,$data_name,$data_email){
		require 'phpmailer/PHPMailer.php';
		require 'phpmailer/Exception.php';
		require 'phpmailer/SMTP.php';

		$mail = new PHPMailer\PHPMailer\PHPMailer();
		try {
		    
		    require 'phpmailer/Config.php';
		    
		    $mail->addAddress($data_email, $data_name);

		    //Content
		    $mail->isHTML(true);
		    $mail->Subject = 'Forgot Password';
		    $mail->Body    = "
		    <html>
				<head></head>
				<body>
					<p>Hi, ".$data_name."</p>
					<p>To reset your password, you need to copy and paste token below less than 10 minutes before expired. This will allow you to choose a new password.</p>
					<p>Token is: <b>".$token."</b></p>
					<p>If you did not request this email, you may safely ignore it.</p>
					<br/>
					<p>Regards,</p>
					<p>CV. Wisnu Cahaya Label</p>
					<p>https://www.wisnucahayalabel.com</p>
				</body>
			</html>";
		    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		    $mail->send();
		    echo "<div class='alert alert-success'>A password reset request has been emailed to you. Please follow the instructions in that email.</div>";
		    echo "<div class='alert alert-warning'><strong><a href='reset.php?token=".$token."'>Click here to reset password!</a></strong></div>";

		} catch (Exception $e) {
			require 'connect.php';
			$query_delete = "DELETE FROM forgot_password WHERE token='$token'";
			$sql_delete = $connect->query($query_delete);
			if($sql_delete){
				echo "<div class='alert alert-danger'>Something went wrong, please try again.</div>";
			}
		}
	}
}

?>