<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$error_message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crime = $_POST['crime'];
    $location = $_POST['location'];
    $crime_date = $_POST['crime_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO crimes (user_id, crime, location, crime_date, description)
            VALUES ('$user_id', '$crime', '$location', '$crime_date', '$description')";

    if ($conn->query($sql) === true) {
        $error_message = "Crime report submitted successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['user_id'];
$sql = "SELECT * FROM crimes WHERE user_id = $userID";
$result = $conn->query($sql);

$crimeReports = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $crimeReports[] = $row;
    }
}

function getStatusClass($status)
{
    switch ($status) {
        case 'Pending':
            return 'status-pending';
        case 'In Progress':
            return 'status-in-progress';
        case 'Closed':
            return 'status-closed';
        default:
            return '';
    }
}

$conn->close();
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .content-section {
            padding-top: 60px;
        }
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">CrimeWatch</a>
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
                        <a class="nav-link" href="user_reports.php">Reports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="forum.php">Forum</a>
                    </li>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-expanded="false">
                            <?php
                            if (!isset($_SESSION['user_id'])) {
                                header("Location: login.php");
                                exit;
                            }
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "project";
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $user_id = $_SESSION['user_id'];
                            $sql = "SELECT lname FROM users WHERE user_id = $user_id";
                            $result = $conn->query($sql);
                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $user_name = $row['lname'];
                                echo $user_name;
                            } else {
                                echo "My Account";
                            }
                            ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>



    <div class="container my-5 content-section">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <br>
                    <h1 class="mb-5">User Dashboard</h1>
                    <div class="card-header">
                        <h2 class="card-title">Report Status</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Report ID</th>
                                    <th>Crime</th>
                                    <th>Description</th>
                                    <th>Date Submitted</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($crimeReports as $report): ?>
                                    <tr>
                                        <td>
                                            <?= $report['crime_id']; ?>
                                        </td>
                                        <td>
                                            <?= $report['crime']; ?>
                                        </td>
                                        <td>
                                            <?= $report['description']; ?>
                                        </td>
                                        <td>
                                            <?= $report['crime_date']; ?>
                                        </td>
                                        <td>
                                            <span class="<?= getStatusClass($report['status']); ?>">
                                                <?= $report['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="container">
                        <br>
                        <h2>Report a Crime</h2>
                        <form action="dashboard.php" method="POST">
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
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
                    <br>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <br>
                        <h2 class="card-title">Update Profile</h2>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="update_profile.php">
                            <?php if (isset($_SESSION['message'])): ?>
                                <?php if (strpos($_SESSION['message'], 'Password do not match') !== false): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $_SESSION['message']; ?>
                                    </div>
                                <?php elseif (strpos($_SESSION['message'], 'Profile updated successfully') !== false): ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $_SESSION['message']; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $_SESSION['message']; ?>
                                    </div>
                                <?php endif; ?>
                                <?php unset($_SESSION['message']); ?>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label for="inputName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="inputName" name="name" value="doe">
                            </div>
                            <div class="mb-3">
                                <label for="inputEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="inputEmail" name="email"
                                    value="doe@gmail.com">
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="inputPassword" name="password">
                            </div>
                            <div class="mb-3">
                                <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="inputConfirmPassword"
                                    name="confirm_password">
                            </div>
                            <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




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