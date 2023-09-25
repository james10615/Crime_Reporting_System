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

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
$sql = "SELECT * FROM crimes";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $crimeId = $_POST['crime_id'];
        $newStatus = $_POST['status'];

        $updateSql = "UPDATE crimes SET status='$newStatus' WHERE crime_id='$crimeId'";
        if ($conn->query($updateSql) === TRUE) {
            header("Location: master_homepage.php");
            exit;
        } else {
            echo "Error updating status: " . $conn->error;
        }
    }

    if (isset($_POST['delete_report'])) {
        $crimeId = $_POST['crime_id'];
        $deleteSql = "DELETE FROM crimes WHERE crime_id='$crimeId'";
        if ($conn->query($deleteSql) === TRUE) {
            header("Location: master_homepage.php");
            exit;
        } else {
            echo "Error deleting crime report: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Crimes Reported Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        .container {
            margin-top: 80px;
        }

        .card {
            margin-bottom: 20px;
        }

        .card-header {
            font-weight: bold;
        }

        .status-pending {
            color: #ff9800;
        }

        .status-in-progress {
            color: #2196f3;
        }

        .status-closed {
            color: #4caf50;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Admin Crimes Reported Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="reports_admin.php">Reports</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="master_logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Crime Reports Dashboard</h1>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        All Crime Reports
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Crime ID</th>
                                    <th>User ID</th>
                                    <th>Crime</th>
                                    <th>Location</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($crimeReports) > 0): ?>
                                    <?php foreach ($crimeReports as $report): ?>
                                        <tr>
                                            <td>
                                                <?= $report['crime_id']; ?>
                                            </td>
                                            <td>
                                                <?= $report['user_id']; ?>
                                            </td>
                                            <td>
                                                <?= $report['crime']; ?>
                                            </td>
                                            <td>
                                                <?= $report['location']; ?>
                                            </td>
                                            <td>
                                                <?= $report['crime_date']; ?>
                                            </td>
                                            <td>
                                                <?= $report['description']; ?>
                                            </td>
                                            <td>
                                                <span class="<?= getStatusClass($report['status']); ?>">
                                                    <?= $report['status']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="crime_id" value="<?= $report['crime_id']; ?>">
                                                    <select name="status">
                                                        <option value="Pending">Pending</option>
                                                        <option value="In Progress">In Progress</option>
                                                        <option value="Closed">Closed</option>
                                                    </select>
                                                    <button type="submit" name="update_status"
                                                        class="btn btn-sm btn-primary">Update</button>
                                                    <button type="submit" name="delete_report"
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No crime reports found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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