<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
$error_message = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $lpassword = mysqli_real_escape_string($conn, $_POST['lpassword']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (empty($lname) || empty($email) || empty($lpassword) || empty($confirm_password)) {
        $error_message = "Please fill in all the required fields";
    } elseif ($lpassword !== $confirm_password) {
        $error_message = "Password and Confirm Password do not match";
    } else {
        $check_email_query = "SELECT * FROM users WHERE email = '$email'";
        $check_email_result = $conn->query($check_email_query);

        if ($check_email_result->num_rows > 0) {
            $error_message = "Error: Email is already registered";
        } else {
            $lname = ucfirst($lname);

            $hashed_password = hash('sha256', $lpassword);

            $insert_query = "INSERT INTO users (lname, email, lpassword) VALUES ('$lname', '$email', '$hashed_password')";

            if ($conn->query($insert_query) === TRUE) {
                header("Location: registration_success.php");
                exit;
            } else {
                $error_message = "Error: " . $insert_query . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System - Register</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-signin {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
        }

        .form-signin label {
            font-weight: 500;
        }

        .form-signin input[type="text"],
        .form-signin input[type="email"],
        .form-signin input[type="password"] {
            height: 50px;
            font-size: 16px;
            border-radius: 5px;
            margin-bottom: 20px;
            padding: 10px;
            box-shadow: none;
            border: none;
            border-bottom: 2px solid #ddd;
        }

        .form-signin input[type="text"]:focus,
        .form-signin input[type="email"]:focus,
        .form-signin input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: none;
        }

        .form-signin .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-size: 18px;
            font-weight: 500;
            margin-top: 20px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .form-signin .btn-primary:hover {
            background-color: #0069d9;
        }

        .form-signin .form-check-label {
            font-weight: 400;
        }
    </style>



    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Crime Reporting System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php#report-crime">Report a Crime</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php#map">Crime Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



</head>

<body>
    <div class="container">
        <form class="form-signin" action="register.php" method="post">
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <h1 class="h3 mb-3 fw-normal">Register</h1>
            <label for="inputName" class="visually-hidden">Name</label>
            <input type="text" id="inputName" name="lname" class="form-control" placeholder="Name" required autofocus>
            <label for="inputEmail" class="visually-hidden">Email address</label>
            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required>
            <label for="inputPassword" class="visually-hidden">Password</label>
            <input type="password" id="inputPassword" name="lpassword" class="form-control" placeholder="Password"
                required>
            <label for="inputConfirmPassword" class="visually-hidden">Confirm Password</label>
            <input type="password" id="inputConfirmPassword" name="confirm_password" class="form-control"
                placeholder="Confirm Password" required>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="agreement" required>
                <label class="form-check-label" for="agreement">
                    I agree to the terms and conditions
                </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        </form>
        </main>
    </div>


    <footer class="bg-light">
        <div class="container text-center">
            <p>&copy; 2023 Crime Reporting System</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
        </script>

</body>

</html>