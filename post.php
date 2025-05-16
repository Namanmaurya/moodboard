<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood = mysqli_real_escape_string($conn, $_POST['mood']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $user_id = $_SESSION['user_id'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $img_name = time() . '_' . basename($_FILES['image']['name']);
        $target = 'uploads/' . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $img_name;
        }
    }

    $query = "INSERT INTO moods (user_id, mood_emoji, message, image) 
              VALUES ('$user_id', '$mood', '$message', '$image')";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Failed to post your mood.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post Mood - MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            font-family: 'Segoe UI', sans-serif;
        }

        h3 {
            font-size: 2.2rem;
            color: #007bff;
            text-align: center;
            margin: 40px 0 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            background: #fff;
            max-width: 600px;
            margin: auto;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-select,
        .form-control {
            border-radius: 12px;
            font-size: 1rem;
            padding: 10px 15px;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
        }

        .upload-input {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .btn-primary {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .btn-secondary {
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 15px;
        }

        @media (max-width: 576px) {
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h3>Share Your Mood 😄</h3>
        <div class="card">
            <?php if (!empty($error))
                echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="mood" class="form-label">Choose Your Mood Emoji</label>
                    <select name="mood" id="mood" class="form-select" required>
                        <option value="😄">😄 Happy</option>
                        <option value="😢">😢 Sad</option>
                        <option value="😡">😡 Angry</option>
                        <option value="🤔">🤔 Thoughtful</option>
                        <option value="😍">😍 In Love</option>
                        <option value="😴">😴 Tired</option>
                        <option value="😎">😎 Cool</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea name="message" id="message" rows="4" class="form-control"
                        placeholder="What's on your mind?" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Optional Image</label>
                    <input type="file" name="image" class="form-control upload-input" accept="image/*">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Post Mood</button>
                    <a href="index.php" class="btn btn-secondary">Back to Feed</a>
                </div>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>