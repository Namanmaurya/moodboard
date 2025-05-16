<?php
include 'includes/db.php';
session_start();

if (!isset($_GET['user_id'])) {
    header("Location: index.php");
    exit();
}

$profile_user_id = (int) $_GET['user_id'];

$user_query = "SELECT * FROM users WHERE id = '$profile_user_id'";
$user_result = mysqli_query($conn, $user_query);
$profile_user = mysqli_fetch_assoc($user_result);

if (!$profile_user) {
    echo "User not found!";
    exit();
}

$profile_image = !empty($profile_user['profile']) ? 'uploads/' . $profile_user['profile'] : 'assets/images/default_profile.png';
$bio = !empty($profile_user['bio']) ? $profile_user['bio'] : "This user hasn't added a bio yet.";
$dob = !empty($profile_user['dob']) ? date("F d, Y", strtotime($profile_user['dob'])) : "Not provided";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            font-weight: 600;
        }

        p {
            margin-bottom: 5px !important;
        }

        .profile-header {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .profile-pic {
            width: 150px;
            object-fit: contain;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .card-title_emoji {
            font-size: 2rem;
            margin-right: 10px;
            display: inline-block;
        }

        .card img {
            width: 300px;
            object-fit: contain;
        }

        .btn-outline-primary {
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
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
            font-weight: 600;
            margin-bottom: 2px;
        }

        .comment-time {
            font-size: 0.8rem;
            color: #888;
            margin-top: 2px;
        }

        .toggle-buttons {
            margin-bottom: 1rem;
            display: flex;
            gap: 10px;
        }

        @media screen and (max-width: 768px) {
            .profile-header .row {
                text-align: center;
            }

            .profile-header .col-md-3,
            .profile-header .col-md-5 {
                margin-bottom: 15px;
            }

            .profile-pic {
                width: 120px;
                height: 120px;
            }

            .card-title_emoji {
                font-size: 1.5rem;
            }

            .comment-form {
                flex-direction: column;
                gap: 8px;
            }

            .comment-form input[type="text"] {
                width: 100%;
            }

            .comment-form button {
                width: 100%;
            }
        }

        @media screen and (max-width: 480px) {
            h2 {
                font-size: 1.5rem;
            }

            .profile-pic {
                width: 100px;
                height: 100px;
            }
        }
    </style>

</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Profile Header -->
                <div class="profile-header text-center mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <img src="<?php echo $profile_image; ?>" alt="Profile Picture" class="profile-pic">
                        </div>
                        <div class="col-md-5 text-md-start">
                            <h2>@<?php echo htmlspecialchars($profile_user['name']); ?></h2>
                            <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($bio)); ?></p>
                            <p><strong>Date of Birth:</strong> <?php echo $dob; ?></p>
                        </div>
                        <div class="col-md-2">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Moods Grid -->
            <?php if (mysqli_num_rows($moods_result) > 0): ?>
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($moods_result)): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <div class="card-title_emoji"><?php echo $row['mood_emoji']; ?></div>
                                        <small class="text-muted float-end">
                                            <?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?>
                                        </small>
                                    </h5>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>

                                    <div class="d-flex gap-2 mb-3">
                                        <?php if (!empty($row['image'])): ?>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-view-image"
                                                data-bs-toggle="modal" data-bs-target="#imageModal"
                                                data-image-src="uploads/<?php echo $row['image']; ?>">
                                                View Image
                                            </button>
                                        <?php endif; ?>

                                        <button type="button" class="btn btn-outline-primary btn-sm btn-toggle-comments">
                                            View Comments
                                        </button>
                                    </div>

                                    <!-- Comment Form -->
                                    <form action="comment.php" method="POST" class="d-flex comment-form mb-3">
                                        <input type="hidden" name="mood_id" value="<?php echo $row['id']; ?>">
                                        <input type="text" name="comment" class="form-control me-2"
                                            placeholder="Write a comment..." required>
                                        <button type="submit" class="btn btn-primary btn-sm">Post</button>
                                    </form>

                                    <!-- Comments -->
                                    <?php
                                    $moodId = $row['id'];
                                    $commentQuery = "SELECT moods_comments.comment, moods_comments.created_at, users.name
                                         FROM moods_comments
                                         JOIN users ON moods_comments.user_id = users.id
                                         WHERE moods_comments.mood_id = '$moodId'
                                         ORDER BY moods_comments.created_at ASC";
                                    $commentResult = mysqli_query($conn, $commentQuery);
                                    ?>
                                    <div class="comments" style="display:none;">
                                        <?php while ($comment = mysqli_fetch_assoc($commentResult)): ?>
                                            <div class="comment">
                                                <div class="comment-author"><?php echo htmlspecialchars($comment['name']); ?>:</div>
                                                <span><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></span>
                                                <div class="comment-time">
                                                    <?php echo date("M d, h:i A", strtotime($comment['created_at'])); ?>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">This user hasn't shared any moods yet.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0">
                    <img src="" alt="Mood Image" id="modalImage" class="rounded" style="width:100%;" />
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle comments visibility
            document.querySelectorAll('.btn-toggle-comments').forEach(btn => {
                btn.addEventListener('click', function () {
                    // Find closest card-body parent
                    const cardBody = this.closest('.card-body');
                    const comments = cardBody.querySelector('.comments');
                    if (!comments) return;
                    if (comments.style.display === 'none' || comments.style.display === '') {
                        comments.style.display = 'block';
                        this.textContent = 'Hide Comments';
                    } else {
                        comments.style.display = 'none';
                        this.textContent = 'View Comments';
                    }
                });
            });

            // Show image in modal
            const imageModal = document.getElementById('imageModal');
            imageModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const imageSrc = button.getAttribute('data-image-src');
                const modalImage = imageModal.querySelector('#modalImage');
                modalImage.src = imageSrc;
            });

            // Clear modal image on close
            imageModal.addEventListener('hidden.bs.modal', function () {
                const modalImage = imageModal.querySelector('#modalImage');
                modalImage.src = '';
            });
        });
    </script>
</body>

</html>