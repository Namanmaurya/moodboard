<?php
include 'includes/db.php';
session_start();

// Get mood owner to notify
$moodQuery = mysqli_query($conn, "SELECT user_id FROM moods WHERE id = '$mood_id'");
$mood = mysqli_fetch_assoc($moodQuery);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood_id = isset($_POST['mood_id']) ? (int) $_POST['mood_id'] : 0;
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if (!empty($comment) && $mood_id > 0) {
        $query = "INSERT INTO moods_comments (mood_id, user_id, comment) VALUES ('$mood_id', '$user_id', '$comment')";
        mysqli_query($conn, $query);
    }

    header("Location: index.php");
    exit();
}

// Notify the owner (except self-comments)
if ($mood['user_id'] != $user_id) {
    $insertNotif = "INSERT INTO notifications (recipient_id, sender_id, mood_id, type) 
                    VALUES ('{$mood['user_id']}', '$user_id', '$mood_id', 'comment')";
    mysqli_query($conn, $insertNotif);
}
?>
