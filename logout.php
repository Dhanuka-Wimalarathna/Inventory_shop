<?php
  // Include necessary files and functions
  require_once('includes/load.php');

  // Attempt to log the user out, then redirect to the login page
  if(!$session->logout()) {
    redirect("index.php");
  }
?>
