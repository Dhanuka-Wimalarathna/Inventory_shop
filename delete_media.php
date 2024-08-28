<?php
  // Include the necessary files and functions
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(2);
?>

<?php
  // Attempt to find the media file by its ID, which is passed through the URL
  $find_media = find_by_id('media', (int)$_GET['id']);
  
  // Create a new Media object
  $photo = new Media();
  
  // Attempt to delete the media file using its ID and file name
  if($photo->media_destroy($find_media['id'], $find_media['file_name'])){
      // Success: Display a success message and redirect to the media page
      $session->msg("s","Photo has been deleted.");
      redirect('media.php');
  } else {
      // Failure: Display an error message and redirect to the media page
      $session->msg("d","Photo deletion failed or missing parameters.");
      redirect('media.php');
  }
?>
