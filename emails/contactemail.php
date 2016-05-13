<?php
error_reporting(E_ALL);



require 'vendor/phpmailer/phpmailer/class.phpmailer.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
// Set PHPMailer to use the sendmail transport
$mail->isSendmail();
//Set who the message is to be sent from
$mail->setFrom('tech@novainteriorslv.com', 'Nova Interiors');
//Set an alternative reply-to address
$mail->addReplyTo('tech@novainteriorslv.com', 'Nova Tech Email');
//Set who the message is to be sent to
$mail->addAddress('dimor22@gmail.com', 'David Lopez');
//Set the subject line
$mail->Subject = 'PHPMailer sendmail test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML('<p>thml content here <strong>bold</strong></p>');
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
} else {
	echo "Message sent!";
}


//echo 'emailer script page';