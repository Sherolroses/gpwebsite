<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST["phone"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?status=invalid");
        exit();
    }

    $receiverEmail = "sherolroses05@gmail.com";

    $emailSubject = "New Contact Message: " . ($subject ?: "No Subject");
    $emailBody = "You have received a new message from your website contact form:\n\n"
        . "Name: $name\n"
        . "Email: $email\n"
        . "Phone: $phone\n"
        . "Subject: $subject\n"
        . "Message:\n$message\n";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sherolroses05@gmail.com'; // Your Gmail address
        $mail->Password = 'ewcqsklcghcajuqv';     // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sherolroses05@gmail.com', 'Website Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress($receiverEmail);
        $mail->Subject = $emailSubject;
        $mail->Body = $emailBody;

        $mail->send();

        // Redirect back to contact page with success status
        header("Location: contact.php?status=success");
        exit();

    } catch (Exception $e) {
        // Redirect back to contact page with error status
        header("Location: contact.php?status=error");
        exit();
    }
} else {
    // If not a POST request, redirect to contact form
    header("Location: contact.php");
    exit();
}
?>
