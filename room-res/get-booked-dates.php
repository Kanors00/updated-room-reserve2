<?php
header('Content-Type: application/json');

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'room_reservation');

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Query all booked dates
$result = $conn->query("SELECT booking_date FROM reservations");

$dates = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['booking_date']; // format: YYYY-MM-DD
    }
    $result->free();
}

echo json_encode($dates);

$conn->close();