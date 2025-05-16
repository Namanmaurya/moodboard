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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Daily MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        body {
            background: linear-gradient(135deg, #f0f9ff, #e0f7fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-top: 50px !important;
        }

        h3 {
            font-weight: bold;
            color: #007BFF;
        }

        .btn-success {
            background-color: #28a745;
            border-radius: 25px;
            font-weight: 600;
            padding: 10px 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            max-height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #007BFF;
        }

        .card-text {
            font-size: 15px;
            color: black;
            margin-top: 5px;
        }

        .btn-outline-primary,
        .btn-primary,
        .btn-danger {
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #007BFF;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .text-muted {
            font-size: 0.875rem;
        }

        .comment {
            background-color: #f8f9fa;
            border-left: 3px solid #007BFF;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.95rem;
        }

        .comment-author {
            font-weight: bold;
        }

        .comment-time {
            font-size: 0.8rem;
            color: #888;
        }

        .mood-container {
            margin-bottom: 40px;
        }

        .form-control {
            border-radius: 20px;
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: #007BFF;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .comments .fw-semibold {
            font-size: 0.95rem;
        }

        img.img-fluid {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
        }

        .input-group .btn {
            padding-left: 20px;
            padding-right: 20px;
        }

        a.fw-semibold.text-dark:hover {
            color: #0056b3;
        }

        /* Buttons container for toggles */
        .toggle-buttons {
            margin-bottom: 1rem;
            display: flex;
            gap: 10px;
        }

        /* Hide comments section initially */
        .comments {
            display: none;
        }

        /* Modal image */
        #imageModal .modal-dialog {
            max-width: 600px;
        }

        #imageModal img {
            width: 100%;
            border-radius: 15px;
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

        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 mood-container shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <!-- User Info & Timestamp -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="fs-4"><?php echo $row['mood_emoji']; ?></span>
                                        <a href="profile.php?user_id=<?php echo $row['user_id']; ?>"
                                            class="fw-semibold text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </a>
                                    </div>
                                    <small
                                        class="text-muted"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
                                </div>

                                <!-- Mood Message -->
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                                <!-- Buttons to toggle image and comments -->
                                <div class="toggle-buttons">
                                    <?php if (!empty($row['image'])): ?>
                                        <button type="button" class="btn btn-outline-primary btn-sm btn-view-image" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="uploads/<?php echo $row['image']; ?>">
                                            View Image
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-toggle-comments">
                                        View Comments
                                    </button>
                                </div>

                                <!-- Mood Image (hidden by default) -->
                                <?php if (!empty($row['image'])): ?>
                                    <div class="mb-3 text-center mood-image-container" style="display:none;">
                                        <img src="uploads/<?php echo $row['image']; ?>" class="img-fluid rounded"
                                            style="max-height: 300px; object-fit: cover;" alt="Mood Image" />
                                    </div>
                                <?php endif; ?>

                                <!-- Like Button & Count -->
                                <div class="d-flex align-items-center mb-3 mt-auto">
                                    <a href="like.php?mood_id=<?php echo $row['id']; ?>"
                                        class="btn btn-sm <?php echo ($row['user_liked'] > 0) ? 'btn-danger' : 'btn-outline-primary'; ?>">
                                        <?php echo ($row['user_liked'] > 0) ? '💔' : '❤️'; ?>
                                    </a>
                                    <span class="ms-2"><?php echo $row['likes_count']; ?> </span>
                                </div>

                                <!-- Comment Form -->
                                <form action="comment.php" method="POST" class="input-group mb-3">
                                    <input type="hidden" name="mood_id" value="<?php echo $row['id']; ?>">
                                    <input type="text" name="comment" class="form-control" placeholder="Write a comment..." required>
                                    <button type="submit" class="btn btn-outline-primary">Post</button>
                                </form>

                                <!-- Comments Section (hidden by default) -->
                                <?php
                                $moodId = $row['id'];
                                $commentQuery = "SELECT moods_comments.comment, moods_comments.created_at, users.name
                                    FROM moods_comments
                                    JOIN users ON moods_comments.user_id = users.id
                                    WHERE moods_comments.mood_id = '$moodId'
                                    ORDER BY moods_comments.created_at ASC";
                                $commentResult = mysqli_query($conn, $commentQuery);
                                ?>
                                <div class="comments">
                                    <?php while ($comment = mysqli_fetch_assoc($commentResult)): ?>
                                        <div class="py-2 comment">
                                            <div class="fw-semibold text-dark">
                                                <?php echo htmlspecialchars($comment['name']); ?>
                                                <small
                                                    class="text-muted ms-2"><?php echo date("M d, h:i A", strtotime($comment['created_at'])); ?></small>
                                            </div>
                                            <div class="text-muted"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-info">No moods yet. Be the first to share!</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0">
                    <img src="" alt="Mood Image" id="modalImage" class="rounded" />
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle comments visibility
        document.querySelectorAll('.btn-toggle-comments').forEach(button => {
            button.addEventListener('click', () => {
                const cardBody = button.closest('.card-body');
                const commentsSection = cardBody.querySelector('.comments');
                if (commentsSection.style.display === 'none' || !commentsSection.style.display) {
                    commentsSection.style.display = 'block';
                    button.textContent = 'Hide Comments';
                } else {
                    commentsSection.style.display = 'none';
                    button.textContent = 'View Comments';
                }
            });
        });

        // Image modal functionality
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const bsModal = new bootstrap.Modal(imageModal);

        document.querySelectorAll('.btn-view-image').forEach(button => {
            button.addEventListener('click', () => {
                const imgSrc = button.getAttribute('data-image-src');
                modalImage.src = imgSrc;
                bsModal.show();
            });
        });
    </script>
</body>

</html>
