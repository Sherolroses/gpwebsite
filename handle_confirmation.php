<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

$host = 'localhost';
$dbname = 'u295875224_YeIs3';
$username = 'u295875224_OKC1I';
$password = 'Kussnqosherol2003';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bookingID = $_POST['booking_id'] ?? null;
$action = $_POST['action'] ?? '';

if (!$bookingID || !in_array($action, ['confirm', 'reject'])) {
    echo "Invalid request.";
    exit();
}

$status = $action === 'confirm' ? 'confirmed' : 'rejected';

// Update status
$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $bookingID);
$stmt->execute();

// Get patient email
$result = $conn->query("SELECT fullname, email, date, time FROM bookings WHERE id = $bookingID");
$row = $result->fetch_assoc();
$fullname = $row['fullname'];
$email = $row['email'];
$date = $row['date'];
$time = $row['time'];

$subject = "Appointment " . ucfirst($status);
$message = "Dear $fullname,\n\nYour appointment on $date at $time has been $status by the doctor.\n\nRegards,\nDr Mopp's Practice";

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
    $mail->addAddress($email);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();

    echo "<h2>Booking $status</h2><p>Patient has been notified.</p>";
} catch (Exception $e) {
    echo "Error sending email: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
