<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $message = $_POST['message'];

    if (empty($title) || empty($message)) {
        echo "Please fill in all fields.";
    } else {

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

        $sql = "INSERT INTO forum_messages (user_id, message_content, title) VALUES ('$user_id', '$message', '$title')";
        if ($conn->query($sql) === TRUE) {
            $error_message = "Message posted successfully!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Community Forum</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="forum.php">Community Forum</a>
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
    <br><br><br>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM forum_messages";
    $result = $conn->query($sql);

    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="container mt-4">
                    <h1>Welcome to the Community Forum!</h1>
                    <p class="lead">Join the discussion and connect with other community members</p>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Community Guidelines</h5>
                                    <p class="card-text">Please keep the conversation respectful and civil. Any posts
                                        containing hate speech or personal attacks will be removed. Let's work together
                                        to build a safe and supportive community.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h2>Recent Posts</h2>
                        </div>
                    </div>

                    <?php
                    $user_id = "";
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                    }

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $postDate = date("F j, Y", strtotime($row['timestamp']));
                            $author = "Unknown";

                            echo '
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">' . $row['title'] . '</h5>
                                            <p class="card-text">' . $row['message_content'] . '</p>
                                            <a href="#" class="card-link">Read More</a>
                                        </div>
                                        <div class="card-footer text-muted">
                                        Posted on ' . $row['timestamp'] . ' by User id: ' . $row['user_id'] . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                        }
                    } else {
                        echo '<p>No messages found.</p>';
                    }
                    ?>

                </div>
            </div>

            <div class="col-md-4"><br><br><br><br>
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title">Add Post</h5>

                        <form method="POST" action="forum.php">
                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="post_title">Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group">
                                <label for="post_content">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    </main>

    <footer class="container py-5">

        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3" style="background-color: rgba(255, 255, 255, 0.2);">
                © 2023 Crime Reporting System:
            </div>
        </footer>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>

</body>

</html>