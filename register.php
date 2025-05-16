<?php
include 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Profile pic upload
    $profilePic = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = uniqid() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
            $profilePic = $fileName;
        }
    }

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered!";
    } else {
        $query = "INSERT INTO users (name, email, password, dob, bio, profile_pic)
                  VALUES ('$name', '$email', '$password', '$dob', '$bio', '$profilePic')";
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
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
           background: linear-gradient(to right, #f8f9fa, #e0f7fa);
            text-align: center;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        h2 {
            font-weight: bold;
            color: #007BFF;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007BFF;
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

        /* Responsive Tweaks */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .btn-primary {
                font-size: 0.95rem;
            }

            .brand-logo {
                width: 50px;
                height: 50px;
            }
        }
    </style>

</head>

<body>
    <div class="login-card text-center">
        <h2>Create Your MoodBoard</h2>
        <p class="text-muted mb-4">Start sharing your daily moods and inspirations!</p>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (!empty($error))
            echo "<div class='alert alert-danger'>$error</div>";
        ?>

        <form method="POST" action="" enctype="multipart/form-data" class="text-start">
            <div class="row g-3">
                <!-- Name -->
                <div class="col-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name"
                        required>
                </div>

                <!-- Email -->
                <div class="col-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="example@mail.com"
                        required>
                </div>

                <!-- Date of Birth -->
                <div class="col-md-6">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" id="dob" required>
                </div>

                <!-- Profile Picture -->
                <div class="col-md-6">
                    <label for="profile_pic" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control" id="profile_pic" accept="image/*"
                        required>
                </div>

                <!-- Bio -->
                <div class="col-12">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" id="bio" rows="1" placeholder="Tell us about yourself..."
                        required></textarea>
                </div>

                <!-- Password -->
                <div class="col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password"
                        placeholder="Minimum 6 characters" required minlength="6">
                </div>

                <!-- Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                </div>
            </div>
        </form>



        <div class="mt-3 text-muted">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>

</html>