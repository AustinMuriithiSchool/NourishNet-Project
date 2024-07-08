<?php
session_start();
include 'config.php';

// Remove session entry from the database
if (isset($_SESSION['session_id'])) {
    $session_id = $_SESSION['session_id'];
    $stmt = $conn->prepare("DELETE FROM sessions WHERE session_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: loginusers.php");
exit();
?>

