<?php

require __DIR__ . '/vendor/autoload.php';
use Mailtrap\Config;
use Mailtrap\MailtrapSandboxClient;
use PHPMailer\PHPMailer\PHPMailer;

// Initialize Mailtrap Client
$apiKey = '7ebd30e0246cce418c6996a770e6b8d4'; // Replace with your Mailtrap API Key
$mailtrap = new MailtrapSandboxClient(new Config($apiKey));

// Get form data
$emailAddress = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['body'] ?? '';


$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Username = '6095ad997b31c7';
$mail->Password = '2702c4371c6e1d';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom(address: $emailAddress, name: '');
$mail->addAddress(address: 'andreeabohus315@gmail.com', name: '');
$mail->Subject = $subject;

$mail->isHTML(true);
$mail->Body = $message;


if (!$mail->send()) {
  echo 'Message could not be sent.';
  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  header('Location: success.html');
}


