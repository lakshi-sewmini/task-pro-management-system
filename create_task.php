<?php 
session_start();
include 'db_config.php'; 

//  SECURITY CHECK: If the logged in user is not 'admin',they will be redirected to the dashbaord(index.php)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if(isset($_POST['submit'])){
    $title = $_POST['task_title'];
    $desc = $_POST['description'];
    $user = $_POST['assign_to'];
    $prio = $_POST['priority'];
    
    // Tasks insertion(Prepared Statement)
    $stmt1 = $conn->prepare("INSERT INTO tasks (task_title, description, assigned_to, priority) VALUES (?, ?, ?, ?)");
    $stmt1->bind_param("ssis", $title, $desc, $user, $prio);
    $stmt1->execute();
    
    // Notifications insertion
    $notif_msg = "Add new task: " . $title;
    $stmt2 = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
    $stmt2->bind_param("s", $notif_msg);
    $stmt2->execute();
    
    header("Location: all_tasks.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Task</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content-area">
            <h2>Create New Task</h2>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group"><label>Task Title</label><input type="text" name="task_title" required></div>
                    <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
                    <div class="form-group"><label>Assign To</label>
                        <select name="assign_to">
                            <?php 
                            $users = $conn->query("SELECT * FROM users");
                            while($u = $users->fetch_assoc()) echo "<option value='{$u['id']}'>{$u['username']}</option>";
                            ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Priority</label>
                        <select name="priority"><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option></select>
                    </div>
                    <button type="submit" name="submit" class="submit-btn">Save Task</button>
                    <button type="reset" class="submit-btn">Clear Form</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>