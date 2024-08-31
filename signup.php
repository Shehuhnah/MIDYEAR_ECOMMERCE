<?php
include 'db.php';
$showModal = false; // Flag to control modal visibility

if (isset($_POST['submit'])) {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists
    $checkEmailQuery = $conn->prepare("SELECT email FROM signup WHERE email = ?");
    $checkEmailQuery->bind_param("s", $email);
    $checkEmailQuery->execute();
    $result = $checkEmailQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Email already exists. Please choose a different email.</p>";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the statement to insert data
        $sql = $conn->prepare("INSERT INTO signup (firstname, lastname, username, email, password) VALUES (?, ?, ?, ?, ?)");
        if ($sql) {
            $sql->bind_param("sssss", $firstname, $lastname, $username, $email, $hashedPassword);

            if ($sql->execute()) {
                $showModal = true; // Set flag to true on successful sign-up
            } else {
                echo "<p>Error: " . $sql->error . "</p>";
            }
        } else {
            echo "<p>Error preparing statement: " . $conn->error . "</p>";
        }
    }

    $checkEmailQuery->close();
    if (isset($sql)) {
        $sql->close();
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
    <title>Sign Up</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row p-1 box-area" style="width: 55%; height:600px; border-radius: 30px">
            <!-- left box -->
            <div class="col-md-6 rounded-5 d-flex justify-content-center align-items-center flex-column left-box ">
                <div class="featured-image">
                    <img src="loginpic.png" class="img-fluid" alt="lbox" id="lboximg">
                </div>
                <h2> NAURS MAKEUP</h2>
            </div>
            <!-- right box -->
            <div class="col-md-6 right-box rounded-5 ">
                <div class="row align-items-center d-flex justify-content-center">
                    <div class="header-text mb-4 text-center">
                        <h2 class="text-light">Create an Account</h2>
                        <h6 class="fs-6 text-light">We are happy to have you back</h6>
                    </div>
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col pe-1">
                                <input type="text" class="form-control form-control-md" placeholder="First Name" name="firstname" required> 
                            </div>
                            <div class="col ps-1 mb-3">
                                <input type="text" class="form-control form-control-md" placeholder="Last Name" name="lastname" required>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-md" placeholder="Username" name="username" required>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control form-control-md" placeholder="Email Address" name="email" required>
                        </div>
                        <div class="input-group mb-4">
                            <input type="password" class="form-control form-control-md" placeholder="Password" name="password" required>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg w-100 fs-6 btn-light" name="submit" value="Submit"> Signup</button>
                        </div>
                    </form>
                    <div class="input-group mb-3 d-flex justify-content-center">
                        <div class="input-group mb-3">
                            <button class="btn btn-lg w-100 fs-6"><a href="login.php" class="text-light"> Log In Here!</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="signupModalLabel">Success!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your account has been created successfully.
          </div>
          <div class="modal-footer">
            <a href="login.php" class="btn btn-primary">Log In</a>
          </div>
        </div>
      </div>
    </div>

    <script>
        <?php if ($showModal): ?>
            // Show the modal
            var signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
            signupModal.show();
        <?php endif; ?>
    </script>
</body>
</html>
