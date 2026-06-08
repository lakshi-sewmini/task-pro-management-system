<?php
include 'db_config.php';

// Deleting all data in the table
$sql = "DELETE FROM notifications";

if ($conn->query($sql) === TRUE) {
    // If successfully deleted, it will be sent back to the notifications page
    header("Location: notifications.php?msg=cleared");
    exit();
} else {
    // If there is a mistake, please show it
    echo "Error clearing notifications: " . $conn->error;
}
?>