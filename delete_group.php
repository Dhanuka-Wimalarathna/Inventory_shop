<?php
  // Include the necessary files and functions
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);
?>

<?php
  // Attempt to delete the user group based on the ID passed through the URL
  $delete_id = delete_by_id('user_groups', (int)$_GET['id']);
  
  // Check if the deletion was successful
  if($delete_id){
      // Success: Display a success message and redirect to the group page
      $session->msg("s","Group has been deleted.");
      redirect('group.php');
  } else {
      // Failure: Display an error message and redirect to the group page
      $session->msg("d","Group deletion failed or missing parameters.");
      redirect('group.php');
  }
?>

