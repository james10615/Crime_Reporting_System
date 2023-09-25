<?php
if (isset($_POST['reset_request'])) {

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "project";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $query = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);

  if ($user) {
    $token = bin2hex(random_bytes(32));
    $query = "INSERT INTO reset_password (email, token) VALUES('$email', '$token')";
    mysqli_query($conn, $query);

    $to = $email;
    $subject = "Reset Your Password";
    $message = "Please click the following link to reset your password: http://localhost/project/reset_password_confirm.php?token=$token";
    $headers = "From: noreply@example.com";
    mail($to, $subject, $message, $headers);

    header("Location: reset_password_confirmation.php");
    exit();
  } else {
    echo "Invalid email address.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <style>
    .two {
      width: 35.7%;
      margin-left: 10.3cm;
      margin: auto;
    }

    .container {
      margin-top: 5cm;
    }
  </style>
</head>

<body>
  <div class="container">
    <form class="form-signin" method="post" action="reset_password.php">
      <h1 class="h3 mb-3 font-weight-normal two">Reset Password</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input type="email" id="inputEmail" class="form-control two" placeholder="Email address" name="email" required
        autofocus><br>
      <button class="btn btn-lg btn-primary btn-block two" type="submit" name="reset_request">Reset
        Password</button>
    </form>
  </div>


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