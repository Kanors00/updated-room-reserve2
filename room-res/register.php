<?php
include 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = trim($_POST['fName']);
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $role = 'user'; // Force role to 'user' regardless of input

  if ($password !== $confirmPassword) {
    echo "<script>
      alert('Passwords do not match.');
      window.location.href = 'index.php?register=failed&reason=password_mismatch';
    </script>";
    exit;
  }

  $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
  $check->bind_param("ss", $username, $email);
  $check->execute();
  $check->store_result();

  if ($check->num_rows > 0) {
    header("Location: index.php?register=failed&reason=exists");
    exit;
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $fullName, $username, $email, $hashedPassword, $role);

  if ($stmt->execute()) {
    header("Location: index.php?register=success");
    exit;
  } else {
    header("Location: index.php?register=failed&reason=error");
    exit;
  }
}
?>