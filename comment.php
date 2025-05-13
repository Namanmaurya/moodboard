<?php
include 'includes/db.php';
session_start();

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
?>
