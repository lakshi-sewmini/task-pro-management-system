<?php
session_start();
include 'db_config.php';

// sent to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['new_status'])) {
    
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status'];
    $current_user = $_SESSION['username']; // The name of the logged-in person was taken from the sesion

    // 1. First, the test title is found in the database
    $task_stmt = $conn->prepare("SELECT task_title FROM tasks WHERE id = ?");
    $task_stmt->bind_param("i", $task_id);
    $task_stmt->execute();
    $task_result = $task_stmt->get_result();
    
    if ($task_result->num_rows > 0) {
        $task_row = $task_result->fetch_assoc();
        $task_title = $task_row['task_title'];

        // 2.Updating the stutus of a task
        $update_stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_status, $task_id);
        $update_stmt->execute();

        // 3.Customizing the notification message based on the logged in user name
        // ex: @kasun's 'Create database' task status changed to Completed
        $notif_msg = "@" . $current_user . "'s '" . $task_title . "' task status changed to " . $new_status;

        // 4. Entering the prepared notificaton into the database
        $notif_stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
        $notif_stmt->bind_param("s", $notif_msg);
        $notif_stmt->execute();
    }
    
    // Redirect to the all tasks page again
    header("Location: all_tasks.php");
    exit();
} else {
    header("Location: all_tasks.php");
    exit();
}
?>