<?php
include 'config.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: admin.php");
    exit;
}

if (isset($_POST['submit'])) {
    $sql2 = "SELECT * FROM print";
    $result = mysqli_query($conn, $sql2);
    $row = mysqli_fetch_array($result);

    if (!empty($_POST['select']) && in_array($row['id'], $_POST['select'])) {
        $stmt = $conn->prepare("UPDATE print SET status = 'done' WHERE id = ?");
        $stmt->bind_param("i", $row['id']);
        // download files from specific id without using zip
        $sql = "SELECT * FROM print WHERE id = '" . $row['id'] . "'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $param = $row['file'];
        $array = explode(", ", $param);
        $directory = "uploads/" . $row['phone'];
        foreach ($array as $value) {
            $file = $directory . "/" . $value;
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
            }
        }
    }
}

?>

<html>

<head>
    <title>3K Fast Print - ADMIN</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Print</p>
                <hr>
                <h4 style="text-align: center;">Hello Admin, please check all your printing queue from web upload!</h4>
                <br>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <input type="submit" name="submit" value="Update Status" class="btn btn-primary"><br><br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name of Customer</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Size</th>
                                <th scope="col">Color</th>
                                <th scope="col">Copies</th>
                                <th scope="col">File</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM print where status = 'pending'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $param = $row['file'];
                                    $array = explode(", ", $param);
                                    if (!empty($param)) {
                                        echo "<tr>";
                                        echo "<td><input type='checkbox' name='select[]' value='" . $row['id'] . "'> ". $row['id']."</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['phone'] . "</td>";
                                        echo "<td>" . $row['size'] . "</td>";
                                        echo "<td>" . $row['color'] . "</td>";
                                        echo "<td>" . $row['copies'] . "</td>";
                                        echo "<td>";
                                        foreach ($array as $value) {
                                            echo "<a href='uploads/".$row['phone']."/" . $value . "' target='_blank'>" . $value . "</a><br>";
                                        }
                                        echo "</td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr>";
                                        echo "<td><input type='checkbox' name='select[]' value='" . $row['id'] . "'> ". $row['id']."</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['phone'] . "</td>";
                                        echo "<td>" . $row['size'] . "</td>";
                                        echo "<td>" . $row['color'] . "</td>";
                                        echo "<td>" . $row['copies'] . "</td>";
                                        echo "<td> </td>";
                                        echo "<td>" . $row['status'] . "</td>";
                                        echo "</tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </form>
            </div>
        </div>
</body>

</html>