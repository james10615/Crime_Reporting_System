<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: master_login.php");
    exit;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$userId = $_SESSION['user_id'];

$sql = "SELECT location, COUNT(*) AS count FROM crimes GROUP BY location ORDER BY count DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$mostReportedLocation = $row['location'];
$crimeCount = $row['count'];

$sql = "SELECT location, COUNT(*) AS count FROM crimes WHERE location != '$mostReportedLocation' GROUP BY location";
$result = mysqli_query($conn, $sql);
$otherLocations = array();
while ($row = mysqli_fetch_assoc($result)) {
    $otherLocations[] = $row;
}


$dates = [];
for ($i = 4; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;
}

$crimeCounts = [];
$anonymousUsers = [];
$registeredUsers = [];
foreach ($dates as $date) {
    $sql = "SELECT COUNT(*) AS count,
            SUM(CASE WHEN user_id = 0 THEN 1 ELSE 0 END) AS anonymous,
            SUM(CASE WHEN user_id != 0 THEN 1 ELSE 0 END) AS registered
            FROM crimes WHERE DATE(crime_date) = '$date'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $crimeCounts[] = $row['count'];
    $anonymousUsers[] = $row['anonymous'];
    $registeredUsers[] = $row['registered'];
}

function getCrimeReports($date)
{
    global $conn;
    $sql = "SELECT * FROM crimes WHERE DATE(crime_date) = '$date'";
    $result = mysqli_query($conn, $sql);

    $reports = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }

    return $reports;
}

function getCrimeCountByStatus($date, $status)
{
    global $conn;
    $sql = "SELECT COUNT(*) as count FROM crimes WHERE DATE(crime_date) = '$date' AND status = '$status'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

$currentDate = date('Y-m-d');

if (isset($_POST['date'])) {
    $selectedDate = $_POST['date'];
    $crimeReports = getCrimeReports($selectedDate);
    $pendingCount = getCrimeCountByStatus($selectedDate, 'pending');
    $ongoingCount = getCrimeCountByStatus($selectedDate, 'In progress');
    $closedCount = getCrimeCountByStatus($selectedDate, 'Closed');
    $anonymousUserCount = $anonymousUsers[array_search($selectedDate, $dates)];
    $registeredUserCount = $registeredUsers[array_search($selectedDate, $dates)];
} else {
    $crimeReports = getCrimeReports($currentDate);
    $pendingCount = getCrimeCountByStatus($currentDate, 'pending');
    $ongoingCount = getCrimeCountByStatus($currentDate, 'In progress');
    $closedCount = getCrimeCountByStatus($currentDate, 'Closed');
    $anonymousUserCount = $anonymousUsers[array_search($currentDate, $dates)];
    $registeredUserCount = $registeredUsers[array_search($currentDate, $dates)];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Reporting System - Admin Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="master_homepage.php">Dashboard</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="master_logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Reports</h1>

        <form method="POST">
            <div class="form-group row">
                <label for="date" class="col-sm-2 col-form-label">Select Date:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $currentDate; ?>">
                </div>
                <div class="col-sm-4">
                    <button type="submit" class="btn btn-primary">Get Reports</button>
                </div>
            </div>
        </form>

        <h3 class="mb-3">Crime Reports - Date:
            <?php echo $selectedDate ?? $currentDate; ?>
        </h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Crime ID</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Crime</th>
                    <th scope="col">Location</th>
                    <th scope="col">Date</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crimeReports as $report): ?>
                    <tr>
                        <td>
                            <?php echo $report['crime_id']; ?>
                        </td>
                        <td>
                            <?php echo $report['user_id']; ?>
                        </td>
                        <td>
                            <?php echo $report['crime']; ?>
                        </td>
                        <td>
                            <?php echo $report['location']; ?>
                        </td>
                        <td>
                            <?php echo $report['crime_date']; ?>
                        </td>
                        <td>
                            <?php echo $report['description']; ?>
                        </td>
                        <td>
                            <?php
                            $status = $report['status'];
                            if ($status == 'pending') {
                                echo '<span class="badge badge-warning">Pending</span>';
                            } elseif ($status == 'In progress') {
                                echo '<span class="badge badge-primary">In Progress</span>';
                            } elseif ($status == 'Closed') {
                                echo '<span class="badge badge-success">Closed</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="mb-3">Users who Reported Crimes - Date:
            <?php echo $selectedDate ?? $currentDate; ?>
        </h3>
        <div class="row">
            <div class="col-md-6">
                <h5>Crimes by Status</h5>
                <ul>
                    <li>Pending:
                        <?php echo $pendingCount; ?>
                    </li>
                    <li>In Progress:
                        <?php echo $ongoingCount; ?>
                    </li>
                    <li>Closed:
                        <?php echo $closedCount; ?>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>Users who Reported Crimes</h5>
                <ul>
                    <li>Anonymous Users:
                        <?php echo $anonymousUserCount; ?>
                    </li>
                    <li>Registered Users:
                        <?php echo $registeredUserCount; ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container mt-5">
            <h3 class="mb-3">Location with Most Reported Crimes</h3>
            <p>Location:
                <?php echo $mostReportedLocation; ?>
            </p>
            <p>Number of Crimes:
                <?php echo $crimeCount; ?>
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

        <h3 class="mb-3">Crime Statistics - Date:
            <?php echo $selectedDate ?? $currentDate; ?>
        </h3>
        <div class="row">
            <div class="col-md-12">
                <h5>Number of Crimes reported for the past five Days</h5>
                <canvas id="crimeChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('crimeChart').getContext('2d');
        var crimeData = {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Crimes',
                data: <?php echo json_encode($crimeCounts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };
        var crimeChart = new Chart(ctx, {
            type: 'line',
            data: crimeData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });
    </script>

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