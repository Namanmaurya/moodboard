<?php
include 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Already logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MoodBoard</title>
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
        <h2>Login to MoodBoard</h2>
        <p class="text-muted mb-4">Start curating your daily vibes!</p>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>";
        ?>

        <form method="POST" action="">
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com" required>
            </div>
            <div class="mb-4 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>

        <div class="mt-3 text-muted">
            Don't have an account? <a href="register.php">Sign up here</a>
        </div>
    </div>
</body>
</html>
