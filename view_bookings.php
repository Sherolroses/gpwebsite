<?php
$conn = new mysqli("localhost", "u295875224_OKC1I", "Kussnqosherol2003", "u295875224_YeIs3");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM bookings WHERE status = 'pending' ORDER BY date, time");

echo "<h2>Pending Bookings</h2>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>{$row['fullname']}</strong> - {$row['date']} at {$row['time']} | 
        <a href='confirm_booking.php?id={$row['id']}'>Confirm/Reject</a></p>";
    }
} else {
    echo "No pending bookings.";
}

$conn->close();
?>
