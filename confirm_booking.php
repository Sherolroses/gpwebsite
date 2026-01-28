<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$conn = new mysqli("localhost", "u295875224_OKC1I", "Kussnqosherol2003", "u295875224_YeIs3");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if (!$id) {
    die("No booking selected.");
}

// Get booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found.");
}

if ($action === 'confirm' || $action === 'reject') {
    $newStatus = $action === 'confirm' ? 'confirmed' : 'rejected';

    $updateStmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $updateStmt->bind_param("si", $newStatus, $id);
    $updateStmt->execute();

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sherolroses05@gmail.com';
        $mail->Password   = 'ewcqsklcghcajuqv';  // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('sherolroses05@gmail.com', 'gp practice');
        $mail->addAddress($booking['email'], $booking['fullname']);

        $mail->isHTML(true);
        $mail->Subject = "Your Booking Has Been " . ucfirst($newStatus);
        $mail->Body    = "Dear {$booking['fullname']},<br><br>Your appointment on {$booking['date']} at {$booking['time']} has been <strong>$newStatus</strong>.";

        $mail->send();
        echo "Booking $newStatus and email sent successfully.";
    } catch (Exception $e) {
        echo "Booking $newStatus, but email failed. Mailer Error: {$mail->ErrorInfo}";
    }

    $updateStmt->close();
} else {
    echo "<h3>Confirm or Reject Booking</h3>";
    echo "<p><strong>{$booking['fullname']}</strong> — {$booking['date']} at {$booking['time']}</p>";
    echo "<a href='confirm_booking.php?id=$id&action=confirm'>✅ Confirm</a> | ";
    echo "<a href='confirm_booking.php?id=$id&action=reject'>❌ Reject</a>";
}

$stmt->close();
$conn->close();
?>
