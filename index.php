<?php
  // Start output buffering
  ob_start();

  // Include necessary files and functions
  require_once('includes/load.php');

  // Redirect to the home page if the user is already logged in
  if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="login-page">
  <div class="text-center">
    <!-- Display the login panel title -->
    <h1>Login Panel</h1>
    <h4>Inventory Management System - Phone Shop</h4>
  </div>

  <!-- Display any messages (errors, success) -->
  <?php echo display_msg($msg); ?>

  <!-- Login form -->
  <form method="post" action="auth.php" class="clearfix">
    <div class="form-group">
      <!-- Username input field -->
      <label for="username" class="control-label">Username</label>
      <input type="name" class="form-control" name="username" placeholder="Username">
    </div>
    <div class="form-group">
      <!-- Password input field -->
      <label for="Password" class="control-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <div class="form-group">
      <!-- Submit button for login -->
      <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
    </div>
  </form>
</div>

<?php include_once('layouts/footer.php'); ?>
