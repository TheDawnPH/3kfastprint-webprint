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
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Prints</p>
                <hr>
                <h4 style="text-align: center;">Please press the button below to start uploading files for printing</h4>
                <br>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 text-center">
                    <a href="upload.php" class="btn btn-success btn-lg" type="button">Start Uploading</a>
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
            <div class="col-md-12">
                <hr>
                <h1 class="text-center">About Us</h1><br>
                <p>3K Fast Prints is a printing service that offers fast and reliable printing services around Alabang.
                </p>
                <p>We are located Near 4J Dimaya Canteen, Along Alabang-Zapote Road. We are open from 8:00 AM to 5:00
                    PM, Monday to Saturday for Physical Shop, and 24 hours open on Online.</p>
                <p>For more information, please contact us at 09199278009 or email us at
                    prints@thedawnph.onmicrosoft.com</p>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.0533075407293!2d121.02974187387619!3d14.42409058137618!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d1b6c0912195%3A0x1e1a7713e3b494fc!2s3K%20Fast%20Prints!5e0!3m2!1sen!2sph!4v1702271022982!5m2!1sen!2sph"
                    width="350" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <?php include 'footer.php'; ?>
</body>

</html>