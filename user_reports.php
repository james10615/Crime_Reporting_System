<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
$userId = $_SESSION['user_id'];

$sql = "SELECT location, COUNT(*) AS count FROM crimes GROUP BY location ORDER BY count DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$mostReportedLocation = $row['location'];
$crimeCount = $row['count'];

$sql = "SELECT COUNT(*) AS count FROM crimes WHERE user_id = '$userId' AND status = 'closed'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$closedCasesCount = $row['count'];

$sql = "SELECT location, COUNT(*) AS count FROM crimes WHERE location != '$mostReportedLocation' GROUP BY location";
$result = mysqli_query($conn, $sql);
$otherLocations = array();
while ($row = mysqli_fetch_assoc($result)) {
    $otherLocations[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System - User Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="user_reports.php">Your Reports</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>




    <div class="container mt-5">
        <h1 class="mb-4">User Reports</h1>

        <h3 class="mb-3">Location with Most Reported Crimes</h3>
        <p>Location:
            <?php echo $mostReportedLocation; ?>
        </p>
        <p>Number of Crimes:
            <?php echo $crimeCount; ?>
        </p>

        <h3 class="mb-3">Closed Cases you have reported</h3>
        <p>Number of Closed Cases:
            <?php echo $closedCasesCount; ?>
        </p>

        <h3 class="mb-3">Other Locations</h3>
        <ul>
            <?php foreach ($otherLocations as $location): ?>
                <li>Location:
                    <?php echo $location['location']; ?>, Number of Crimes:
                    <?php echo $location['count']; ?>
                </li>
            <?php endforeach; ?>
        </ul>


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