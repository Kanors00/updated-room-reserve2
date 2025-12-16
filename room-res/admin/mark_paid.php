<?php
session_start();
include 'sql.php';

// Only admins can mark reservations as paid
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
  $reservation_id = intval($_POST['reservation_id']);
  $stmt = $conn->prepare("UPDATE reservations SET payment_status = 'paid' WHERE id = ?");
  $stmt->bind_param("i", $reservation_id);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: user-dashboard.php"); // redirect back
exit;
?>