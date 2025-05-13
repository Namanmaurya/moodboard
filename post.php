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
</head>
<body class="bg-light">
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h3>Share Your Mood 😄</h3>
    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>Choose your mood emoji</label>
                    <select name="mood" class="form-select" required>
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
                    <label>Your message</label>
                    <textarea name="message" class="form-control" rows="4" required placeholder="What's on your mind?"></textarea>
                </div>
                <div class="mb-3">
                    <label>Optional image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success">Post Mood</button>
                <a href="index.php" class="btn btn-secondary">Back to Feed</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
