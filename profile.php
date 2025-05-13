<?php
include 'includes/db.php';
session_start();

if (!isset($_GET['user_id'])) {
    header("Location: index.php");
    exit();
}

$profile_user_id = (int) $_GET['user_id'];

// Fetch profile user info
$user_query = "SELECT * FROM users WHERE id = '$profile_user_id'";
$user_result = mysqli_query($conn, $user_query);
$profile_user = mysqli_fetch_assoc($user_result);

if (!$profile_user) {
    echo "User not found!";
    exit();
}

// Fetch moods of this user
$moods_query = "SELECT moods.*, 
                (SELECT COUNT(*) FROM moods_likes WHERE mood_id = moods.id) AS likes_count,
                (SELECT COUNT(*) FROM moods_likes WHERE mood_id = moods.id AND user_id = '{$_SESSION['user_id']}') AS user_liked
                FROM moods 
                WHERE user_id = '$profile_user_id' 
                ORDER BY created_at DESC";
$moods_result = mysqli_query($conn, $moods_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($profile_user['name']); ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <div class="text-center mb-4">
        <h2><?php echo htmlspecialchars($profile_user['name']); ?>'s MoodBoard 🧠</h2>
        <p class="text-muted">@<?php echo strtolower(str_replace(' ', '', $profile_user['name'])); ?></p>
    </div>

    <?php if (mysqli_num_rows($moods_result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($moods_result)): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo $row['mood_emoji']; ?> 
                        <small class="text-muted float-end"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
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
                        <input type="text" name="comment" class="form-control me-2" placeholder="Write a comment..." required>
                        <button type="submit" class="btn btn-primary btn-sm">Post</button>
                    </form>

                    <!-- Show Comments -->
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
                                <small class="text-muted float-end"><?php echo date("M d, h:i A", strtotime($comment['created_at'])); ?></small>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-center text-muted">This user hasn't shared any moods yet.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
