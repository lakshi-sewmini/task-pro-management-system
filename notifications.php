<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <title>Notifications - Task Pro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content-area">
            <div class="header-title" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2><i class="fas fa-bell"></i> Notification board</h2>
                    <p style="color: #666; font-size: 14px;">All system activities are listed here.</p>
                </div>
                <a href="clear_notifications.php" class="clear-btn" style="text-decoration: none; background: #ff4757; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                    <i class="fas fa-trash-alt"></i> Clear All
                </a>
            </div>
            <hr style="margin: 15px 0 25px; border: 0; border-top: 1px solid #eee;">

            <div class="notification-list">
                <?php
                $sql = "SELECT * FROM notifications ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $message = $row['message'];
                        $is_unread = ($row['status'] == 'unread') ? 'unread-bg' : '';
                        
                        $icon_class = "fa-info-circle";
                        $card_type = "info-card";

                        if (strpos($message, 'Completed') !== false) {
                            $icon_class = "fa-check-circle"; $card_type = "success-card";
                        } elseif (strpos($message, 'In Progress') !== false) {
                            $icon_class = "fa-spinner fa-spin"; $card_type = "progress-card";
                        } elseif (strpos($message, 'Pending') !== false) {
                            $icon_class = "fa-clock"; $card_type = "warning-card";
                        }
                ?>
                        <div class="notif-card <?php echo $card_type . ' ' . $is_unread; ?>">
                            <div class="notif-icon-box">
                                <i class="fas <?php echo $icon_class; ?>"></i>
                            </div>
                            <div class="notif-body">
                                <div class="notif-msg">
                                    <?php echo $message; ?>
                                    <?php if($row['status'] == 'unread'): ?>
                                        <span class="new-tag">NEW</span>
                                    <?php endif; ?>
                                </div>
                                <div class="notif-meta">
                                    <span><i class="far fa-calendar-alt"></i> <?php echo date('Y-m-d', strtotime($row['created_at'])); ?></span>
                                    <span><i class="far fa-clock"></i> <?php echo date('H:i A', strtotime($row['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                    // Marking everything as read after viewing the page
                    $conn->query("UPDATE notifications SET status = 'read' WHERE status = 'unread'");
                } else {
                    echo "<div style='text-align:center; padding: 50px; color: #999;'>There are no notifications.</div>";
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>