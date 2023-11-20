<?php
$admin = "<a class='nav-item nav-link' href='admin.php'>Admin</a>";
$logout = "<a class='nav-item nav-link' href='logout.php'>Logout</a>";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php" style="font-family: Kagitingan, sans-serif; font-size: 25px;">3K FAST
        PRINTS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-item nav-link" href="upload.php">Web Print</a></li>
            <li class="nav-item"><?php if (!empty($_SESSION['loggedin'])) { echo $admin; } ?></li>
            <li class="nav-item"><?php if (!empty($_SESSION['loggedin'])) { echo $logout; } ?></li>
        </ul>
    </div>
</nav>