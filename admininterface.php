<?php
include 'config.php';

session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: admin.php");
    exit;
}

function test_input($data) {
    $data = htmlspecialchars($data);
    $data = str_replace("&lt;b&gt;", "<b>", $data);
    $data = str_replace("&lt;/b&gt;", "</b>", $data);
    $data = nl2br($data);
    return $data;
}

// Initialize variables
$title = $content = "";
$title_err = $content_err = "";

// Processing form data when form is submitted
if (isset($_POST['submit2'])) {
        // Check if title is empty
        if (empty(trim($_POST["title"]))) {
            $title_err = '<div class="alert alert-danger" role="alert">Please enter title.</div>';
        } else {
            $title = test_input($_POST["title"]);
        }
    
        // Check if content is empty
        if (empty(trim($_POST["content"]))) {
            $content_err = '<div class="alert alert-danger" role="alert">Please enter content.</div>';
        } else {
            $content = test_input($_POST["content"]);
        }
    
        // Check input errors before inserting in database
        if (empty($title_err) && empty($content_err)) {
            $sql = "INSERT INTO announcement (title, content, date) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $param_title, $param_content, $param_date);
    
            // Set parameters
            $param_title = $title;
            $param_content = $content;
            $param_date = date("Y-m-d h:ia");
    
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $success = '<div class="alert alert-success" role="alert">Announcement has been posted!</div>';
            } else {
                $warning = '<div class="alert alert-warning" role="alert">Something went wrong. Please try again later.</div>';
            }
    
            // Close statement
            mysqli_stmt_close($stmt);
        }
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['select'])) {
        $selectedIds = $_POST['select'];

        foreach ($selectedIds as $id) {
            $stmt = $conn->prepare("UPDATE print SET status = 'done' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();        
        }

        // Optionally, you can add a message to indicate that the download is complete
        // echo "<script>alert('Files download initiated and status updated to done for selected checkboxes!')</script>";

        // Now, you can refresh the page if needed
        // echo '<script>location.reload(true);</script>';
        echo "<script>alert('Records has been updated!')</script>";
    } else {
        echo "<script>alert('Please select at least one checkbox!')</script>";
    }
}
?>



<html>

<head>
    <title>3K Fast Prints - ADMIN</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="b5-min.css">
    <script src="b5-min.js"></script>
    <script src="b5-bundle.min.js"></script>
    <script src="jq.min.css"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    function refreshPage() {
        location.reload(true);
    }
    </script>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!empty($warning)) {
                    echo $warning;
                } else if (!empty($success)) {
                    echo $success;
                }
                ?>
            </div>
            <div class="col-md-12">
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Prints</p>
                <hr>
                <h4 style="text-align: center;">Hello Admin, please check all your printing queue from web upload!</h4>
                <br>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <input type="submit" name="submit" value="Update Status" class="btn btn-primary">
                    <input type="button" value="Refresh Page" class="btn btn-primary" onclick="refreshPage()">
                    <input type="button" value="View Archives" class="btn btn-secondary"
                        onclick="window.location.href='archives.php'"><br><br>
                    <div class="table-responsive">
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
                    </div>
                </form>
                <hr>
            </div>
            <div class="col-md-12">
                <h5>Announcement Maker</h5>
                <p>To Modify or delete announcement post, please modify it directly to database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <input type="text" name="title" class="form-control" placeholder="Enter Title" required><br>
                    <textarea name="content" class="form-control" placeholder="Enter Content" required></textarea><br>
                    <input type="submit" name="submit2" value="Add Announcement" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>