<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mood     = mysqli_real_escape_string($conn, $_POST['mood']);
    $message  = mysqli_real_escape_string($conn, $_POST['message']);
    $user_id  = $_SESSION['user_id'];
    $image    = '';

    // Handle image upload if exists
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
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            font-family: 'Arial', sans-serif;
        }


        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 25px;
            font-weight: bold;
        }

        .btn-secondary {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            border-radius: 25px;
            color: #007bff;
            font-weight: bold;
        }

        .btn-secondary:hover {
            background-color: #e9ecef;
        }

        .alert {
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .mood-selector select {
            border-radius: 10px;
            padding: 10px;
        }

        label {
            font-weight: bold;
        }

        h3 {
            font-size: 2rem;
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-btn {
            margin-top: 15px;
            display: block;
            text-align: center;
        }

        .upload-input {
            font-size: 1rem;
            background-color: #f1f3f5;
            padding: 10px;
            border-radius: 10px;
            color: #007bff;
            cursor: pointer;
            border: none;
        }

        .upload-input:hover {
            background-color: #e9ecef;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group textarea {
            resize: none;
            font-size: 1rem;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h3>Share Your Mood 😄</h3>
        <div class="card p-4">
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="mood">Choose your mood emoji</label>
                    <div class="mood-selector">
                        <select name="mood" class="form-select" id="mood" required>
                            <option value="😄">😄 Happy</option>
                            <option value="😢">😢 Sad</option>
                            <option value="😡">😡 Angry</option>
                            <option value="🤔">🤔 Thoughtful</option>
                            <option value="😍">😍 In Love</option>
                            <option value="😴">😴 Tired</option>
                            <option value="😎">😎 Cool</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Your message</label>
                    <textarea name="message" class="form-control" rows="4" id="message" placeholder="What's on your mind?" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Optional image</label>
                    <input type="file" name="image" class="form-control upload-input" accept="image/*">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Post Mood</button>
                    <a href="index.php" class="btn btn-secondary">Back to Feed</a>
                </div>
            </form>
        </div>

        <div class="back-btn">
            <a href="index.php" class="btn btn-secondary">Back to Feed</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>

</html>
