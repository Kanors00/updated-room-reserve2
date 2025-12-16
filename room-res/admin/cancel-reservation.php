<?php
$conn = new mysqli('localhost', 'root', '', 'room_reservation');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: user-dashboard.php");
exit;