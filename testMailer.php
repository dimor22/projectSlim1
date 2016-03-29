<?php
error_reporting(E_ALL);

require 'vendor/phpmailer/phpmailer/class.phpmailer.php';

$mail = new PHPMailer;

//var_dump($mail);

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'localhost';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@novainteriorslv.com';                 // SMTP username
$mail->Password = 'Novainterior1';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to


$mail->setFrom('tech@novainteriorslv.com', 'Mailer');
$mail->addAddress('dimor22@gmail.com', 'David Lopez');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('tech@novainteriorslv.com', 'Support');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

////$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
////$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML
//
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if($mail->send()) {
	echo 'Message could not be sent.';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	echo 'Message has been sent';
}


//echo 'emailer script page';