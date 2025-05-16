<?php
// Include db and session if not already included
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Get count of unread notifications
$notifQuery = "SELECT COUNT(*) AS unread_count FROM notifications WHERE recipient_id = '$user_id' AND is_read = 0";
$notifResult = mysqli_query($conn, $notifQuery);
$notifData = mysqli_fetch_assoc($notifResult);
$unreadCount = $notifData['unread_count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Daily MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 100px; 
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/moodboard_logo.png" alt="Logo" width="100">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="notifications.php">
                                <i class="fa-solid fa-bell"></i>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="badge bg-danger"><?php echo $unreadCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="post.php"><i class="fa-solid fa-plus"></i> Post Mood</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php?user_id=<?php echo $_SESSION['user_id']; ?>">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger ms-2" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>
