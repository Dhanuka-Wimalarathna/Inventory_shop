<?php
  // Include the necessary files and functions
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);
?>

<?php
  // Attempt to find the category by its ID, which is passed through the URL
  $categorie = find_by_id('categories', (int)$_GET['id']);
  
  // If no category is found, display an error message and redirect to the categories page
  if(!$categorie){
    $session->msg("d","Missing Categorie id.");
    redirect('categorie.php');
  }
?>

<?php
  // Attempt to delete the category using the ID found in the previous step
  $delete_id = delete_by_id('categories', (int)$categorie['id']);
  
  // Check if the deletion was successful
  if($delete_id){
      // Success: Display a success message and redirect to the categories page
      $session->msg("s","Categorie deleted.");
      redirect('categorie.php');
  } else {
      // Failure: Display an error message and redirect to the categories page
      $session->msg("d","Categorie deletion failed.");
      redirect('categorie.php');
  }
?>
