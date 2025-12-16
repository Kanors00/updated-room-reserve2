<?php
// Include the ONE connection file
include 'db_conn.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, full_name, email, password_hash, role, username FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $fullName, $email, $hashedPassword, $role, $dbUsername);
    $stmt->fetch();

    if (is_string($hashedPassword) && password_verify($password, $hashedPassword)) {
      session_regenerate_id(true);

      $_SESSION['id'] = $id;
      $_SESSION['username'] = $dbUsername;
      $_SESSION['role'] = $role;
      $_SESSION['user_email'] = $email;
      // Make sure we save the name for the greeting
      $_SESSION['user_name'] = $fullName; 

      $stmt->close();
      $conn->close();

      if ($role === 'admin') {
        header("Location: /room-res/admin/admin-dashboard.php");
      } else {
        // Redirect standard users to their dynamic dashboard
        header("Location: admin/user-dashboard.php"); 
      }
      exit();
    } else {
      header("Location: login.php?error=invalid");
      exit();
    }
  } else {
    header("Location: login.php?error=notfound");
    exit();
  }
}
?>
