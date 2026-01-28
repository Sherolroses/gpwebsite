<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

// Database connection
$host = 'localhost';
$dbname = 'u295875224_YeIs3';
$username = 'u295875224_OKC1I';
$password = 'Kussnqosherol2003';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = htmlspecialchars($_POST["fullname"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $date = htmlspecialchars($_POST["date"]);
    $time = htmlspecialchars($_POST["time"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format. Please enter a valid email address.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO bookings (fullname, email, date, time, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssss", $fullname, $email, $date, $time);
    if ($stmt->execute()) {
        $bookingID = $stmt->insert_id;

        $doctorEmail = "sherolroses05@gmail.com";
        $confirmLink = "https://lightyellow-ostrich-817676.hostingersite.com/confirm_booking.php?id=$bookingID";
        $doctorSubject = "New Booking Received";  
        $doctorMessage = "New booking received:\n\nName: $fullname\nEmail: $email\nDate: $date\nTime: $time\n\nConfirm this booking:\n$confirmLink";

        $patientSubject = "Your Appointment with Dr Mopp";
        $patientMessage = "Dear $fullname,\n\nYour appointment has been sent for $date at $time.\n\nThank you,\nDr Mopp's Practice";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sherolroses05@gmail.com';
            $mail->Password = 'ewcq sklc ghca juqv';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('sherolroses05@gmail.com', 'Dr Mopp\'s Practice');

            // Doctor email
            $mail->addAddress($doctorEmail);
            $mail->Subject = $doctorSubject;
            $mail->Body = $doctorMessage;
            $mail->send();

            // Patient email
            $mail->clearAddresses();
            $mail->addAddress($email);
            $mail->Subject = $patientSubject;
            $mail->Body = $patientMessage;
            $mail->send();

            // confirmation message
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Booking Confirmed</title>
                <link rel='stylesheet' href='style.css'>
            </head>
            <body>
                <h2>Booking Received</h2>
                <p>Thank you, $fullname! Your appointment on $date at $time has been successfully sent!</p>
                <p><a href='index.html'>Home</a></p>
            </body>
            </html>";

        } catch (Exception $e) {
            echo "Error sending emails: " . $e->getMessage();
        }
    } else {
        echo "Error: Unable to save the booking data.";
    }

    $stmt->close();
    $conn->close();
}
?>
