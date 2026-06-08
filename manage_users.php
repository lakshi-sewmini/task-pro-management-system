<?php 
session_start();
include 'db_config.php'; 

// SECURITY CHECK: If the logged user is not 'admin',they will be redirected to the Dashboard(index.php)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); 
    exit();
}

// 1. Delete section
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// 2. Getting data for editing
$edit_user = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $edit_user = $res->fetch_assoc();
}

// 3. Update section
if (isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $email, $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content-area">
            <h2>User Management</h2>

            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM users");
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>
                                    <a href='manage_users.php?edit_id={$row['id']}' class='btn-edit'>Edit</a>
                                    <a href='manage_users.php?delete_id={$row['id']}' class='btn-delete' onclick='return confirm(\"මකා දැමීමට සහතිකද?\")'>Delete</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <?php if ($edit_user): ?>
            <div class="form-container" style="margin-top: 30px; border-top: 4px solid #4db8ff;">
                <h3>Update user Information</h3>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?php echo $edit_user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $edit_user['email']; ?>" required>
                    </div>
                    <button type="submit" name="update_user" class="submit-btn">Save Changes</button>
                    <a href="manage_users.php" class="reset-btn">Cancel</a>
                </form>
            </div>
            <?php endif; ?>

        </main>
    </div>
</body>
</html>