<?php
include 'config.php';
session_start();

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize variables
$name = $phone = $size = $color = $copies = $status = "";
$name_err = $phone_err = $size_err = $color_err = $copies_err = "";
$filename_array = array();
$warning = $success = $success_heading = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if name is empty
    if (empty(trim($_POST["name"]))) {
        $name_err = '<div class="alert alert-danger" role="alert">Please enter your name.</div>';
    } else {
        $name = trim(test_input($_POST["name"]));
    }

    // Check if phone is empty
    if (empty(trim($_POST["phone"]))) {
        $phone_err = '<div class="alert alert-danger" role="alert">Please enter your phone number.</div>';
    } else {
        $phone = trim(test_input($_POST["phone"]));
    }

    // Check if status is empty
    if (empty(trim($_POST["status"]))) {
        $status = "pending";
    } else {
        $status = trim(test_input($_POST["status"]));
    }

    // Check if size is empty
    if (empty(trim($_POST["size"]))) {
        $size_err = '<div class="alert alert-danger" role="alert">Please select size.</div>';
    } else {
        $size = trim(test_input($_POST["size"]));
    }

    // Check if color is empty
    if (empty(trim($_POST["color"]))) {
        $color_err = '<div class="alert alert-danger" role="alert">Please select color.</div>';
    } else {
        $color = trim(test_input($_POST["color"]));
    }

    // Check if copies is empty
    if (empty(trim($_POST["copies"]))) {
        $copies_err = '<div class="alert alert-danger" role="alert">Please enter number of copies.</div>';
    } else {
        $copies = trim(test_input($_POST["copies"]));
    }

    $countfiles = count($_FILES['files']['name']);
    $totalFileUploaded = 0;
    $filename_array = array();

    for ($i = 0; $i < $countfiles; $i++) {
        $filename = time() . "_" . $_FILES['files']['name'][$i];
        $directory = "uploads/" . $phone;

        // Check if the directory exists, and create it if not
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // Recursive directory creation
            chmod($directory, 0777);
        }

        // Location of the uploaded file
        $location = $directory . "/" . $filename;
        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        // Valid file extensions
        $valid_extensions = array("jpg", "jpeg", "png", "pdf", "docx");

        // Check file extension
        if (in_array(strtolower($extension), $valid_extensions)) {
            // Upload file
            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $location)) {
                array_push($filename_array, $filename);
                $totalFileUploaded++;
            }
        }
    }

    // submit to db
    if (empty($name_err) && empty($phone_err) && empty($size_err) && empty($color_err) && empty($copies_err) && empty($warning)) {
        $filename = implode(", ", $filename_array);
        // Use prepared statement
        $stmt = $conn->prepare("INSERT INTO print (name, phone, size, color, file, status, copies) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $phone, $size, $color, $filename, $status, $copies);

        if ($stmt->execute()) {
            // Get id of last inserted record
            $id = $stmt->insert_id;
            $success_heading = "Your print ID is: " . $id . "";
            $success = "Your files have been uploaded successfully. Please remember your ID code for this printing job:";
        } else {
            $warning = "Something went wrong, please try again.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!-- HTML part -->
<html>

<head>
    <title>3K Fast Prints - Web Upload</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="b5-min.css">
    <script src="b5-bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php if (!empty($_SESSION['loggedin'])) { include 'nav.php'; } ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Prints</p>
                <p style="text-align:center;">Please fill up information below and start uploading.</p>
                <hr>
                <?php
                if (!empty($warning)) {
                    echo $warning;
                } else if (!empty($success)) {
                    echo '<div class="alert alert-success" role="alert">';
                    echo '<h1 class="alert-heading">'. $success_heading . '</h1>';
                    echo $success;
                    echo '</div>';
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name"
                            value="<?php echo $name; ?>" required><br>
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="Enter your phone number"
                            value="<?php echo $phone; ?>" required><br>
                        <label for="size">Size</label>
                        <select name="size" class="form-control" required>
                            <option value="" <?php if(empty($size)) echo "selected"; ?>>Please Select...</option>
                            <option value="short" <?php if($size=="short") echo "selected"; ?>>Short</option>
                            <option value="a4" <?php if($size=="a4") echo "selected"; ?>>A4</option>
                            <option value="long" <?php if($size=="long") echo "selected"; ?>>Long</option>
                        </select><br>
                        <label for="color">Color or Black and White</label>
                        <select name="color" class="form-control" required>
                            <option value="" <?php if(empty($color)) echo "selected"; ?>>Please Select...</option>
                            <option value="black and white" <?php if($color=="black and white") echo "selected"; ?>>
                                Black and White</option>
                            <option value="color" <?php if($color=="color") echo "selected"; ?>>Color</option>
                        </select><br>
                        <label for="copies">How many copies?</label>
                        <input type="text" name="copies" class="form-control" placeholder="Enter number of copies"
                            value="<?php echo $copies; ?>" required><br>
                        <label for="file">Upload File</label>
                        <input type="file" name="files[]" id="files" class="form-control" multiple>
                        <br>
                        <input type="hidden" name="status" value="pending">
                        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>