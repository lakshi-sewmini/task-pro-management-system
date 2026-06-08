<?php 
session_start(); 
include 'db_config.php'; 
?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>All Tasks - Task Pro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content-area">
            <div class="header-title">
                <h2><i class="fas fa-tasks"></i> All Tasks Management</h2>
                <hr style="margin-bottom: 25px; border: 0; border-top: 1px solid #ddd;">
            </div>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Task Name</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT tasks.*, users.username FROM tasks 
                            LEFT JOIN users ON tasks.assigned_to = users.id 
                            ORDER BY tasks.created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status = $row['status'];
                            //Detereming the class that changes color based on stutus
                            $status_class = "";
                            if($status == "Pending") $status_class = "pending-border";
                            else if($status == "In Progress") $status_class = "progress-border";
                            else if($status == "Completed") $status_class = "completed-border";
                            ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo $row['task_title']; ?></td>
                                <td><?php echo $row['username'] ? "@".$row['username'] : "Unassigned"; ?></td>
                                <td><span class="badge <?php echo $row['priority']; ?>"><?php echo $row['priority']; ?></span></td>
                                <td>
                                    <form action="update_status_logic.php" method="POST">
                                        <input type="hidden" name="task_id" value="<?php echo $row['id']; ?>">
                                        <select name="new_status" onchange="this.form.submit()" class="status-dropdown <?php echo $status_class; ?>">
                                            <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="In Progress" <?php if($status == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if($status == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>කිසිදු Task එකක් ඇතුළත් කර නැත.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>