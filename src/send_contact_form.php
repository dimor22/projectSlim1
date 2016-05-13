<?php


require '../vendor/phpmailer/phpmailer/class.phpmailer.php';

$mail = new PHPMailer;
$mail->isSendmail();
$mail->setFrom($_REQUEST['email'], $_REQUEST['name']);
$mail->addReplyTo($_REQUEST['email'], $_REQUEST['name']);
$mail->addAddress('dimor22@gmail.com', 'Nova Interiors Web Form');
$mail->Subject = 'Message From ' + $_REQUEST['name'];
$mail->msgHTML('<p>' . $_REQUEST['message'] . '</p>');
$mail->AltBody = $_REQUEST["message"];


if (!$mail->send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!";
}
