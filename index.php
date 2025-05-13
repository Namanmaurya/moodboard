<?php
include 'includes/db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch mood posts with likes count
$sql = "SELECT moods.*, users.name, 
        (SELECT COUNT(*) FROM moods_likes WHERE mood_id = moods.id) AS likes_count,
        (SELECT COUNT(*) FROM moods_likes WHERE mood_id = moods.id AND user_id = '$user_id') AS user_liked
        FROM moods
        JOIN users ON moods.user_id = users.id
        ORDER BY moods.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Home - Daily MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h3 class="mb-4">Hi, <?php echo htmlspecialchars($user_name); ?>! 👋 How’s your mood today?</h3>
        <a href="post.php" class="btn btn-success mb-4">+ Share Your Mood</a>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $row['mood_emoji']; ?><a href="profile.php?user_id=<?php echo $row['user_id']; ?>"
                                class="text-decoration-none fw-bold">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </a>
                            <small
                                class="text-muted float-end"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
                        </h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/<?php echo $row['image']; ?>" class="img-fluid rounded" alt="Mood Image">
                        <?php endif; ?>
                        <hr>
                        <div>
                            <a href="like.php?mood_id=<?php echo $row['id']; ?>"
                                class="btn btn-sm <?php echo ($row['user_liked'] > 0) ? 'btn-danger' : 'btn-outline-primary'; ?>">
                                <?php echo ($row['user_liked'] > 0) ? '💔 Unlike' : '❤️ Like'; ?>
                            </a>
                            <span class="ms-2"><?php echo $row['likes_count']; ?> Likes</span>
                        </div>

                        <!-- Comment Form -->
                        <form action="comment.php" method="POST" class="d-flex mt-3">
                            <input type="hidden" name="mood_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="comment" class="form-control me-2" placeholder="Write a comment..."
                                required>
                            <button type="submit" class="btn btn-primary btn-sm">Post</button>
                        </form>

                        <!-- Display Comments -->
                        <?php
                        $moodId = $row['id'];
                        $commentQuery = "SELECT moods_comments.comment, moods_comments.created_at, users.name
                                         FROM moods_comments
                                         JOIN users ON moods_comments.user_id = users.id
                                         WHERE moods_comments.mood_id = '$moodId'
                                         ORDER BY moods_comments.created_at ASC";
                        $commentResult = mysqli_query($conn, $commentQuery);
                        ?>
                        <div class="mt-3">
                            <?php while ($comment = mysqli_fetch_assoc($commentResult)): ?>
                                <div class="bg-light p-2 mb-1 rounded">
                                    <strong><?php echo htmlspecialchars($comment['name']); ?>:</strong>
                                    <span><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></span>
                                    <small
                                        class="text-muted float-end"><?php echo date("M d, h:i A", strtotime($comment['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No moods yet. Be the first to share!</p>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>