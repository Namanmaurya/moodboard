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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Daily MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #f8f9fa);
            font-family: 'Arial', sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007BFF;
        }

        .card-text {
            font-size: 1rem;
            color: #333;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 25px;
            font-weight: bold;
        }

        .btn-outline-primary {
            border-radius: 25px;
            border-color: #007BFF;
            color: #007BFF;
        }

        .btn-primary {
            border-radius: 25px;
        }

        .text-muted {
            font-size: 0.9rem;
        }

        .comment {
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 8px;
            margin-top: 8px;
        }

        .comment-author {
            font-weight: bold;
        }

        .comment-time {
            font-size: 0.8rem;
            color: #888;
        }

        .mood-container {
            margin-bottom: 30px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007BFF;
        }
    </style>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h3 class="mb-4 text-center">Hi, <?php echo htmlspecialchars($user_name); ?>! 👋 How’s your mood today?</h3>
        <div class="text-center mb-4">
            <a href="post.php" class="btn btn-success">+ Share Your Mood</a>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-4 mood-container">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php echo $row['mood_emoji']; ?>
                            <a href="profile.php?user_id=<?php echo $row['user_id']; ?>" class="text-decoration-none fw-bold">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </a>
                            <small class="text-muted float-end"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
                        </h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                        
                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/<?php echo $row['image']; ?>" class="img-fluid rounded mb-3" alt="Mood Image">
                        <?php endif; ?>

                        <div class="d-flex align-items-center">
                            <a href="like.php?mood_id=<?php echo $row['id']; ?>" class="btn btn-sm <?php echo ($row['user_liked'] > 0) ? 'btn-danger' : 'btn-outline-primary'; ?>">
                                <?php echo ($row['user_liked'] > 0) ? '💔 Unlike' : '❤️ Like'; ?>
                            </a>
                            <span class="ms-2"><?php echo $row['likes_count']; ?> Likes</span>
                        </div>

                        <!-- Comment Form -->
                        <form action="comment.php" method="POST" class="d-flex mt-4">
                            <input type="hidden" name="mood_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="comment" class="form-control me-2" placeholder="Write a comment..." required>
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
                                <div class="comment">
                                    <div class="comment-author"><?php echo htmlspecialchars($comment['name']); ?>:</div>
                                    <span><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></span>
                                    <small class="comment-time"><?php echo date("M d, h:i A", strtotime($comment['created_at'])); ?></small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">No moods yet. Be the first to share!</div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
