<?php

require __DIR__ . '/vendor/autoload.php';
use Mailtrap\Config;
use Mailtrap\MailtrapSandboxClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;



// Initialize Mailtrap Client
$apiKey = '7ebd30e0246cce418c6996a770e6b8d4'; // Replace with your Mailtrap API Key
$mailtrap = new MailtrapSandboxClient(new Config($apiKey));

// Get form data
$emailAddress = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['body'] ?? '';

// Compose email
$email = (new Email())
  ->from(new Address($emailAddress, 'Sender Name')) // Replace 'Sender Name' with the sender's name
  ->to(new Address('example@mailtrap.io', 'Test Recipient')) // Replace with your Mailtrap email address
  ->subject($subject)
  ->text($message)
  ->html("<html><body><p>{$message}</p></body></html>");

// Send email and inspect response
try {
  $inboxId = '2995345'; // Replace with your Mailtrap Inbox ID
  $response = $mailtrap->sandbox()->emails()->send($email, $inboxId);

  echo 'Email has been sent!';
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}