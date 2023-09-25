<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 0;
$submitMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crime = $_POST['crime'];
    $location = $_POST['location'];
    $crime_date = $_POST['crime_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO crimes (user_id, crime, location, crime_date, description)
            VALUES ('$user_id', '$crime', '$location', '$crime_date', '$description')";

    if ($conn->query($sql) === true) {
        $submitMessage = "Crime report submitted successfully!";
    } else {
        $submitMessage = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>

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
                            <a class="nav-link" href="#hero">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#report-crime">Report a Crime</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#map">Reported crimes</a>
                        </li>
                        <li>


                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "project";
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            session_start();

                            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                                echo '<a class="nav-link" href="dashboard.php">Dashboard</a>';

                            } else {
                                echo '<a class="nav-link" href="login.php">Login</a>';
                            }
                            ?>

                        </li>
                        <li>

                            <?php
                            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT lname FROM users WHERE user_id = $user_id";
                                $result = $conn->query($query);

                                if ($result && $result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $user_name = $row['lname'];
                                } else {
                                    $user_name = "Unknown";
                                }

                                echo '<div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
          ' . $user_name . '
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
          <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
        </ul>
      </div>';
                            } else {
                                echo '';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>



    </header>

    <section id="hero" class="d-flex justify-content-center align-items-center"
        style=" background-size: cover; background-position: center;">
        <div class="container text-center">
            <h1 class="display-4">Report a crime anonymously or register to report crime</h1>
            <p class="lead">Join our community and help keep our streets safe.</p>
            <?php
            if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            } else {
                echo '
    <section id="get-started">

      <a href="register.php"><button class="btn btn-primary">Get Started</button></a>
    </section>
  ';
            }
            ?>
        </div>
    </section>


    <section id="report-crime" class="bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Report a Crime Anonymously Here</h2>
                    <?php if (!empty($submitMessage)): ?>
                        <div class="alert alert-success">
                            <?php echo $submitMessage; ?>
                        </div>
                        <script>
                            window.onload = function () {
                                document.getElementById('report-crime').scrollIntoView();
                            }
                        </script>
                    <?php endif; ?>
                    <form action="homepage.php" method="POST">
                        <div class="form-group">
                            <label for="crime">Crime:</label>
                            <input type="text" class="form-control" id="crime" name="crime"
                                placeholder="Enter type of crime" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" class="form-control" id="location" name="location"
                                placeholder="Enter location of crime" required>
                        </div>
                        <div class="mb-3">
                            <label for="crime-date" class="form-label">Date and Time of Occurrence</label>
                            <input type="datetime-local" class="form-control" id="crime_date" name="crime_date"
                                placeholder="Enter the date and time of the crime">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Enter description of the crime" required></textarea>
                        </div><br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/500x300.png?text=Reported+Crimes" alt="Map of Reported Crimes"
                        class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section id="map">
        <div class="container text-center">
            <h2>Reported Crimes</h2>
            <img src="https://via.placeholder.com/800x500.png?text=Reported+Crimes" alt="Map of Reported Crimes"
                class="img-fluid">
        </div>
    </section>

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