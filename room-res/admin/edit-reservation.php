<?php
 $conn = new mysqli('localhost', 'root', '', 'room_reservation');
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }

 $id         = intval($_POST['id']);
 $date       = $_POST['booking_date'];
 $guests     = intval($_POST['guest_count']);
 $name       = $_POST['user_name'];
 $company    = $_POST['user_company'];
 $contact    = $_POST['user_contact'];
 $email      = $_POST['user_email'];

 $stmt = $conn->prepare("UPDATE reservations SET
   booking_date = ?,
   guest_count = ?,
   user_name = ?,
   user_company = ?,
   user_contact = ?,
   user_email = ?
   WHERE id = ?");

 $stmt->bind_param("sissssi", $date, $guests, $name, $company, $contact, $email, $id);

 $stmt->execute();
 $stmt->close();
 $conn->close();

 header("Location: admin-dashboard.php");
 exit;
?>