<?php
include 'includes/db.php';
session_start();
$user_id = $_SESSION['user_id'];

// Mark all as read
mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE recipient_id = '$user_id'");

$sql = "SELECT notifications.*, users.name, moods.mood_emoji 
        FROM notifications 
        JOIN users ON notifications.sender_id = users.id 
        JOIN moods ON notifications.mood_id = moods.id 
        WHERE notifications.recipient_id = '$user_id' 
        ORDER BY notifications.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifications - Daily MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container mt-4">
    <h3><i class="fa-solid fa-bell"></i> Notifications</h3>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="alert alert-light border">
                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                <?php if ($row['type'] === 'like'): ?>
                    liked your mood <?php echo $row['mood_emoji']; ?>
                <?php elseif ($row['type'] === 'comment'): ?>
                    commented on your mood <?php echo $row['mood_emoji']; ?>
                <?php endif; ?>
                <br>
                <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No new notifications.</p>
    <?php endif; ?>
</div>
</body>
</html>
