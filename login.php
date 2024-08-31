<?php
session_start();
include('db.php');
$error_message = '';
$success_message = '';
$showModal = false; // Flag to control modal visibility

if (isset($_POST['submit'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Query to get user details
    $sql = "SELECT * FROM signup WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Compare hashed password
        if (password_verify($password, $row["password"])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['useremail'] = $row['email'];

            $success_message = "Login successful! Redirecting to homepage...";
            $showModal = true; // Set flag to true on successful login
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with that username!";
    }
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="login.css">
    <title>Log in</title>
</head>
<body>
    <!-- Alert for Success or Error Message -->
    <?php if (!empty($error_message) || !empty($success_message)): ?>
        <div class="alert <?php echo !empty($success_message) ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
            <?php echo !empty($success_message) ? $success_message : $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Modal for Error and Success -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel"><?php echo !empty($success_message) ? 'Success' : 'Login Error'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo !empty($success_message) ? $success_message : $error_message; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main container -->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <!-- Log in section -->
        <div class="row p-1 box-area" style="width: 55%; border-radius: 30px;">
            <!-- Left box -->
            <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box">
                <div class="featured-image">
                    <img src="loginpic.png" class="img-fluid" alt="lbox" id="lboximg">
                </div>
                <h2>Naurs Makeup</h2>
            </div>

            <!-- Right box -->
            <div class="col-md-6 right-box rounded-5">
                <div class="row align-items-center d-flex justify-content-center">
                    <div class="header-text mb-4 text-center">
                        <h1 class="text-light">Welcome back!</h1>
                        <h6 class="fs-6">We are happy to have you back</h6>
                    </div>
                    <form action="" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-md" placeholder="Username" name="username" required>
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" class="form-control form-control-md" placeholder="Password" name="password" required>
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between" id="formCheck">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label for="rememberMe" class="form-check-label text-light">Remember Me?</label>
                            </div>
                            <div class="forgot">
                                <small><a href="#" class="text-light">Forgot Password</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-1 d-flex justify-content-center">
                            <button type="submit" class="btn btn-lg w-100 fs-6 btn-light" name="submit">Login</button>
                        </div>
                    </form>
                    <div class="row">
                        <small>Don't have an Account? <a href="signup.php" class="text-light">Sign Up here</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to trigger modal if there is an error or success -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($showModal): ?>
                var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                feedbackModal.show();
                setTimeout(function() {
                    window.location.href = 'homepage.php';
                }, 2000); // Adjust timeout duration as needed
            <?php endif; ?>
        });
    </script>
</body>
</html>
