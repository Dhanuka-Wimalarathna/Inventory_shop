<?php
  // Start output buffering
  ob_start();

  // Include necessary files and functions
  require_once('includes/load.php');

  // Redirect to home page if the user is already logged in
  if($session->isUserLoggedIn(true)) { 
    redirect('home.php', false);
  }
?>

<div class="login-page">
    <div class="text-center">
       <!-- Display welcome message -->
       <h1>Welcome</h1>
       <p>Sign in to start your session</p>
     </div>
     
     <!-- Display any messages (errors, success) -->
     <?php echo display_msg($msg); ?>

      <!-- Login form -->
      <form method="post" action="auth_v2.php" class="clearfix">
        <div class="form-group">
              <!-- Username input field -->
              <label for="username" class="control-label">Username</label>
              <input type="name" class="form-control" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <!-- Password input field -->
            <label for="Password" class="control-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="password">
        </div>
        <div class="form-group">
                <!-- Submit button for login -->
                <button type="submit" class="btn btn-info pull-right"  >ogin</button>
        </div>L
    </form>
</div>

<!-- Include the header layout -->
<?php include_once('layouts/header.php'); ?>
