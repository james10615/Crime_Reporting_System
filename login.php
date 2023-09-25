<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['lpassword'])) {
        $email = $_POST['email'];
        $lpassword = $_POST['lpassword'];

        $mysqli = new mysqli("localhost", "root", "", "project");

        if ($mysqli->connect_errno) {
            die("Failed to connect to MySQL: " . $mysqli->connect_error);
        }

        $hashed_password = hash('sha256', $lpassword);

        $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ? AND lpassword = ?");
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];

            $_SESSION['user_id'] = $user_id;

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Invalid email or password.";
        }

        $stmt->close();
        $mysqli->close();
    } else {
        $message = "Invalid email or password.";
    }
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System - Log in</title>

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

        .two {
            width: 35.7%;
            margin-left: 10.3cm;
            margin: auto;
        }
    </style>



    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">Crime Reporting System</a>
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

                </ul>
            </div>
        </div>
    </nav>



</head>

<body>

    <div class="container">
        <form class="form-signin" method="post" action="login.php">
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <label for="inputEmail" class="visually-hidden">Email address</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="email" required>
            <label for="inputPassword" class="visually-hidden">Password</label>
            <input type="password" id="inputPassword" name="lpassword" class="form-control" placeholder="Password"
                required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>

        </form><br>
        <a href="register.php">
            <button class="btn btn-lg btn-primary btn-block two">Register</button>
        </a>
        <div class="two"><a href="reset_password.php">Forgot Password ?</a></div>
        <div class="two"><a href="master_login.php">Law Enforecement ?</a></div>

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