<?php
  // Include the necessary files and functions
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(2);
?>

<?php
  // Attempt to find the product by its ID, which is passed through the URL
  $product = find_by_id('products', (int)$_GET['id']);
  
  // If no product is found, display an error message and redirect to the product page
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('product.php');
  }
?>

<?php
  // Attempt to delete the product using the ID found in the previous step
  $delete_id = delete_by_id('products', (int)$product['id']);
  
  // Check if the deletion was successful
  if($delete_id){
      // Success: Display a success message and redirect to the product page
      $session->msg("s","Product deleted.");
      redirect('product.php');
  } else {
      // Failure: Display an error message and redirect to the product page
      $session->msg("d","Product deletion failed.");
      redirect('product.php');
  }
?>
