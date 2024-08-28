<?php 
// Include the load file that likely contains necessary functions and classes
include_once('includes/load.php'); 
?>

<?php
// Define required fields for login (username and password)
$req_fields = array('username','password' );
// Validate that all required fields are filled
validate_fields($req_fields);

// Sanitize the user input to prevent security issues like SQL injection
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)){
  // Authenticate the user by checking the username and password
  $user_id = authenticate($username, $password);
  
  if($user_id){
    // If authentication is successful, start a session for the user
    $session->login($user_id);
    
    // Update the user's last login time in the database
    updateLastLogIn($user_id);
    
    // Set a success message to be displayed to the user
    $session->msg("s", "Welcome to Inventory Management System");
    
    // Redirect the user to the admin dashboard
    redirect('admin.php', false);
  } else {
    // If authentication fails, set an error message
    $session->msg("d", "Sorry, Username/Password incorrect.");
    
    // Redirect the user back to the login page
    redirect('index.php', false);
  }
} else {
   // If there are validation errors, set the error message
   $session->msg("d", $errors);
   
   // Redirect the user back to the login page
   redirect('index.php', false);
}
?>
