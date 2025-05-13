<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Registered successfully! Please login.";
            header('Location: login.php');
            exit();
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MoodBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            max-width: 450px;
            width: 100%;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        .login-card h2 {
            font-weight: bold;
            color: #007BFF;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007BFF;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .btn-primary {
            border-radius: 50px;
        }

        .text-muted a {
            text-decoration: none;
            color: #007BFF;
        }

        .text-muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card text-center">
        <img src="assets\images\moodboard_logo.png" alt="MoodBoard Logo" class="brand-logo">
        <h2>Create Your MoodBoard</h2>
        <p class="text-muted mb-4">Start sharing your daily moods and inspirations!</p>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>";
        ?>

        <form method="POST" action="">
            <div class="mb-3 text-start">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Your full name" required>
            </div>
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" required>
            </div>
            <div class="mb-4 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Create a password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <div class="mt-3 text-muted">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
