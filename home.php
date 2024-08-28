<?php
// Set the page title and include the necessary files
$page_title = 'Home Page';
require_once('includes/load.php');

// Check if the user is logged in; if not, redirect to the login page
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Display any messages (e.g., success or error messages) -->
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel">
      <!-- Jumbotron for the welcome message -->
      <div class="jumbotron text-center">
        <h1>Welcome !
        </h1>
        <p>Browse around to find out the pages that you can access!</p>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>