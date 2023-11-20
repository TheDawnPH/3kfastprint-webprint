<?php
// mkdir and change permission upload folder in windows
$cmd = "mkdir uploads && icacls uploads /grant Everyone:F /t";
exec($cmd);

date_default_timezone_set('Asia/Manila');

// Database connection
define('DB_SERVER', "localhost");
define('DB_USERNAME', "root");
define('DB_PASSWORD', "");
define('DB_NAME', "print-service");
define('DB_PORT', 3306);

// Try connecting to the Database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check the connection
if ($conn == false) {
    dir('Error: Cannot connect');
} else {
    // create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS `print-service`.`print` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `phone` VARCHAR(255) NOT NULL , `size` VARCHAR(255) NOT NULL , `color` VARCHAR(255) NOT NULL , `file` TEXT NOT NULL , `copies` int(255) NOT NULL , `status` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    $sql2 = "CREATE TABLE IF NOT EXISTS `print-service`.`users` ( `id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `pass` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    $sql5 = "CREATE TABLE IF NOT EXISTS `print-service`.`announcement` ( `id` INT NOT NULL AUTO_INCREMENT , `title` TEXT NOT NULL , `content` TEXT NOT NULL , `date` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
    // execute query
    if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql2) && mysqli_query($conn, $sql5)) {
        // echo "Table created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }

    // check if admin user exists
    $sql3 = "SELECT * FROM users WHERE username = 'admin'";
    $result = mysqli_query($conn, $sql3);
    if (mysqli_num_rows($result) == 0) {
        // create admin user
        $password = password_hash('L4dbcxz1937!', PASSWORD_DEFAULT);
        $sql4 = "INSERT INTO `users` (`id`, `username`, `pass`) VALUES (1, 'admin', '$password')";
            if (mysqli_query($conn, $sql4)) {
                // echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            // echo "Admin user already exists";
        }
    }