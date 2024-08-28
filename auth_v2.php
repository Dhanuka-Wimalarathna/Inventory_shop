<?php 
// Include the file that loads all necessary functions and variables
include_once('includes/load.php'); 
?>

<?php
// Define the required fields for validation
$req_fields = array('username','password');
validate_fields($req_fields);

// Sanitize the input to remove any unwanted characters
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

// Check if there are no validation errors
if(empty($errors)){

    // Authenticate the user using the provided username and password
    $user = authenticate_v2($username, $password);

    // Check if the authentication was successful
    if($user):
        // Create a session with the user's ID
        $session->login($user['id']);
        // Update the last login time for the user
        updateLastLogIn($user['id']);
        
        // Redirect the user based on their user level
        if($user['user_level'] === '1'):
            // Admin level user
            $session->msg("s", "Hello ".$user['username'].", Welcome to OSWA-INV.");
            redirect('admin.php', false);
        elseif ($user['user_level'] === '2'):
            // Special user level
            $session->msg("s", "Hello ".$user['username'].", Welcome to OSWA-INV.");
            redirect('special.php', false);
        else:
            // Regular user
            $session->msg("s", "Hello ".$user['username'].", Welcome to OSWA-INV.");
            redirect('home.php', false);
        endif;

    else:
        // If authentication fails, display an error message and redirect to login page
        $session->msg("d", "Sorry, Username/Password incorrect.");
        redirect('index.php', false);
    endif;

} else {
    // If there are validation errors, display them and redirect to the login page
    $session->msg("d", $errors);
    redirect('login_v2.php', false);
}
?>
