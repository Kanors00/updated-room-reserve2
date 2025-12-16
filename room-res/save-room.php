<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] ?? null) !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $room_name     = trim($_POST['room_name'] ?? '');
    $room_capacity = intval($_POST['room_capacity'] ?? 0);

    if ($room_name === '' || $room_capacity < 1) {
        header("Location: home.php?error=invalid_data");
        exit();
    }

    // Upload handling
    $targetDir = __DIR__ . "/uploads/";
    $publicDir = "/room-res/uploads/";       

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }


    if (!isset($_FILES['room_image']) || $_FILES['room_image']['error'] !== UPLOAD_ERR_OK) {
        header("Location: home.php?error=upload_failed");
        exit();
    }

    $originalName = basename($_FILES["room_image"]["name"]);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif", "webp", "avif"];
    if (!in_array($ext, $allowed)) {
        header("Location: home.php?error=invalid_type");
        exit();
    }

    // Unique filename
    $safeBase    = preg_replace('/[^a-zA-Z0-9-_]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
    $newFileName = time() . "_" . $safeBase . "." . $ext;
    $targetPath  = $targetDir . $newFileName;
    $dbImagePath = $publicDir . $newFileName;

    if (!move_uploaded_file($_FILES["room_image"]["tmp_name"], $targetPath)) {
        header("Location: home.php?error=save_failed");
        exit();
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO room_details (room_name, capacity, image_path) VALUES (?, ?, ?)");
    if (!$stmt) {
        unlink($targetPath); // cleanup orphaned file
        header("Location: home.php?error=prepare_failed");
        exit();
    }

    $stmt->bind_param("sis", $room_name, $room_capacity, $dbImagePath);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: home.php?success=1");
        exit();
    } else {
        $stmt->close();
        unlink($targetPath); // cleanup orphaned file
        header("Location: home.php?error=db_failed");
        exit();
    }
}
