<?php

//Server settings
$mail->SMTPDebug = 0;                                 // Disable verbose debug output, enabled 1=client, 2=server
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'ssl://srv21.niagahoster.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'me@hadrian.my.id';                 // SMTP username
$mail->Password = 'fRK#XL*Rv?-C';                     // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->SMTPSecure = 'ssl';

//Recipients
$mail->setFrom('me@hadrian.my.id', 'CV Wisnu Cahaya Label');

?>