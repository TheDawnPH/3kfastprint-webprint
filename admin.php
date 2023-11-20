<?php
include 'config.php';

session_start();
if (isset($_SESSION['loggedin'])) {
    header("location: admininterface.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = '<div class="alert alert-danger" role="alert">Please enter username.</div>';
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["pass"]))) {
        $password_err = '<div class="alert alert-danger" role="alert">Please enter your password.</div>';
    } else {
        $password = trim($_POST["pass"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT username, pass FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {

                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: admininterface.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = '<div class="alert alert-danger" role="alert">The password you entered was not valid.</div>';
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = '<div class="alert alert-danger" role="alert">No account found with that username.</div>';
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
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
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p style="font-family: Kagitingan, sans-serif; font-size: 60px; text-align: center;">3K Fast Prints</p>
                <p style="text-align:center;">Login as ADMIN</p>
                <hr>
                <?php
                if (!empty($username_err) || !empty($password_err)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo $username_err;
                    echo $password_err;
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control">
                    <label class="form-label">Password</label>
                    <input type="password" name="pass" class="form-control">
                    <br>
                    <input type="submit" class="btn btn-primary mb-3" value="Login">
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>