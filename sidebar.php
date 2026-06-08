<nav class="sidebar">
    <div class="brand">
        <span>Task <span>Pro</span></span>
        <i class="fas fa-bars"></i>
    </div>

    <ul class="nav-links">
        <li><a href="index.php"><i class="fas fa-th-large"></i> Dashboard</a></li>
        
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="manage_users.php"><i class="fas fa-users-cog"></i> Manage Users</a></li>
            <li><a href="create_task.php"><i class="fas fa-plus-square"></i> Create Task</a></li>
        <?php endif; ?>
        
        <li><a href="all_tasks.php"><i class="fas fa-tasks"></i> All Tasks</a></li>
        <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
