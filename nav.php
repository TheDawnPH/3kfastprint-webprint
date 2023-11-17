<?php
$admin = "<a class='nav-item nav-link' href='admin.php'>Admin</a>";
$logout = "<a class='nav-item nav-link' href='logout.php'>Logout</a>";
?>

<nav class="d-print-none navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#" style="font-family: Kagitingan, sans-serif; font-size: 25px;">3K FAST PRINT</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="upload.php">Web Print</a>

      <?php if (!empty($_SESSION['loggedin'])) { echo $admin; } ?>
      <?php if (!empty($_SESSION['loggedin'])) { echo $logout; } ?>

    </div>
  </div>
</nav>
