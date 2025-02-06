<?php

include "../database/database.php";

try {
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $subject = $_POST['subject'];
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE todo SET title = ?, description = ?, subject = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $description, $subject, $id);

    if ($stmt->execute()) {
      header("Location: ../index.php");
      exit;
    } else {
      echo "operation failed";
    }
  }
} catch (\Exception $e) {
  echo "Error: " . $e;
}
