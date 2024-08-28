<?php
  // Include the necessary files and functions
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(3);
?>

<?php
  // Attempt to find the sale by its ID, which is passed through the URL
  $d_sale = find_by_id('sales', (int)$_GET['id']);
  
  // If no sale is found, display an error message and redirect to the sales page
  if(!$d_sale){
    $session->msg("d","Missing sale id.");
    redirect('sales.php');
  }
?>

<?php
  // Attempt to delete the sale using the ID found in the previous step
  $delete_id = delete_by_id('sales', (int)$d_sale['id']);
  
  // Check if the deletion was successful
  if($delete_id){
      // Success: Display a success message and redirect to the sales page
      $session->msg("s","Sale deleted.");
      redirect('sales.php');
  } else {
      // Failure: Display an error message and redirect to the sales page
      $session->msg("d","Sale deletion failed.");
      redirect('sales.php');
  }
?>

