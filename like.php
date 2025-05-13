<?php
include 'includes/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$mood_id = isset($_GET['mood_id']) ? (int) $_GET['mood_id'] : 0;

// Check if the mood exists
$query = "SELECT * FROM moods WHERE id = '$mood_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 0) {
    // Mood doesn't exist
    header("Location: index.php");
    exit();
}

// Check if the user has already liked the mood
$check_like = mysqli_query($conn, "SELECT * FROM moods_likes WHERE mood_id = '$mood_id' AND user_id = '$user_id'");
if (mysqli_num_rows($check_like) > 0) {
    // If already liked, remove the like (unlike)
    $delete_like = mysqli_query($conn, "DELETE FROM moods_likes WHERE mood_id = '$mood_id' AND user_id = '$user_id'");
} else {
    // If not liked, insert the like
    $insert_like = mysqli_query($conn, "INSERT INTO moods_likes (mood_id, user_id) VALUES ('$mood_id', '$user_id')");
}

// Redirect back to the index page (mood feed)
header("Location: index.php");
exit();
