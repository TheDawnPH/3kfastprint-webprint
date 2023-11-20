<?php
include 'config.php';
session_start();

// grab all announcement from database
$sql = "SELECT * FROM announcement ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<html>

<head>
    <title>3K Fast Prints</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="b5-min.css">
    <script src="b5-bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Prints</p><hr>
                <h4 style="text-align: center;">Please press the button below to start uploading files for printing</h4><br>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 text-center">
                    <a href="upload.php" class="btn btn-primary btn-lg" type="button">Start Uploading</a>
                </div>
                <hr>
            </div>
                <div class="col-md-12">
                    <h1 class="text-center">Announcements</h1><br>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="alert alert-secondary" role="alert">';
                            echo '<h2 class="alert-heading">'. $row['title'] . '</h2>';
                            echo '<p>' . $row['content'] . '</p>';
                            echo '<hr>';
                            echo '<p class="mb-0">Posted on: ' . $row['date'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-info" role="alert">';
                        echo '<h5 class="alert-heading">No announcements yet</h4>';
                        echo '<p>There are no announcements yet, please check back later.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>