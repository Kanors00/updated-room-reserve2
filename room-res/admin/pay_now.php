<?php
session_start();
include 'sql.php';

// Only allow users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
  $reservation_id = intval($_POST['reservation_id']);

  // Instead of changing status, just log a payment request
  $stmt = $conn->prepare("UPDATE reservations SET payment_requested = 1 WHERE id = ? AND user_email = ?");
  $stmt->bind_param("is", $reservation_id, $_SESSION['user_email']);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: user-dashboard.php?payment=initiated");
exit;
?>