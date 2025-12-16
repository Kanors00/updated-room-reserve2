<?php
session_start();
include 'sql.php';

// Protect route
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
  header("Location: ../login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipt'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $upload_dir = "uploads/receipts/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES['receipt']['name']);
    $target_file = $upload_dir . $file_name;

    // Validate file type (only images or PDF)
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($_FILES['receipt']['type'], $allowed_types)) {
        $_SESSION['error'] = "Invalid file type. Only JPG, PNG, or PDF allowed.";
        header("Location: user-dashboard.php");
        exit;
    }

    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $target_file)) {
        // Save path in DB
        $stmt = $conn->prepare("UPDATE reservations SET receipt_path = ? WHERE id = ? AND user_email = ?");
        $stmt->bind_param("sis", $target_file, $reservation_id, $_SESSION['user_email']);
        $stmt->execute();
        $stmt->close();
        $_SESSION['success'] = "Receipt uploaded successfully!";
    } else {
        $_SESSION['error'] = "Failed to upload receipt.";
    }
}

header("Location: user-dashboard.php");
exit;
?>