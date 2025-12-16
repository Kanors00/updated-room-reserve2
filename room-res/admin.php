<?php
header('Content-Type: application/json');
session_start(); // ✅ Add this to access session variables

// Connect to database
$conn = new mysqli('localhost', 'root', '', 'room_reservation');

if ($conn->connect_error) {
  echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room_id      = $_POST['room_id'];
  $user_name    = $_POST['user_name'];
  $user_company = $_POST['user_company'];
  $user_contact = $_POST['user_contact'];
  $booking_date = $_POST['booking_date'];
  $guest_count  = $_POST['guest_count'];

  // ✅ Use session email instead of form input
  $user_email = $_SESSION['user_email'];

  // Basic validation
  if (!$user_name || !$booking_date) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
  }

  $check = $conn->query("SELECT COUNT(*) AS count FROM reservations WHERE room_id = '$room_id' AND booking_date = '$booking_date'");
  $row = $check->fetch_assoc();

  if ($row['count'] > 0) {
    echo json_encode(['status' => 'error', 'message' => 'This room is already booked for the selected date.']);
    exit;
  }

  $insert = $conn->query("INSERT INTO reservations 
        (room_id, user_name, user_company, user_contact, user_email, booking_date, guest_count)
        VALUES ('$room_id', '$user_name', '$user_company', '$user_contact', '$user_email', '$booking_date', '$guest_count')");

  if ($insert) {
    echo json_encode(['status' => 'success', 'message' => 'Booking confirmed.']);
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
  }
}

$conn->close();
