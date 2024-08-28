<?php
  // Set the page title to 'Change Password'
  $page_title = 'Change Password';

  // Include the required file that contains necessary functions and connections
  require_once('includes/load.php');

  // Check if the user has the required permission level (3) to access this page
  page_require_level(3);
?>

<?php 
  // Get the current logged-in user's details
  $user = current_user(); 
?>

<?php
  // Check if the form has been submitted
  if(isset($_POST['update'])){

    // Define required fields for form validation
    $req_fields = array('new-password','old-password','id');
    // Validate the fields to ensure they are not empty
    validate_fields($req_fields);

    // If there are no validation errors, proceed
    if(empty($errors)){

      // Check if the old password entered by the user matches the current password
      if(sha1($_POST['old-password']) !== current_user()['password'] ){
        // If not, display an error message and redirect to the change password page
        $session->msg('d', "Your old password does not match");
        redirect('change_password.php',false);
      }

      // Get the user ID and the new password (hashed with sha1)
      $id = (int)$_POST['id'];
      $new = remove_junk($db->escape(sha1($_POST['new-password'])));

      // Update the user's password in the database
      $sql = "UPDATE users SET password ='{$new}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);

      // Check if the update was successful and if a row was affected
      if($result && $db->affected_rows() === 1):
        // If successful, log out the user and prompt them to log in with the new password
        $session->logout();
        $session->msg('s',"Login with your new password.");
        redirect('index.php', false);
      else:
        // If the update failed, display an error message and redirect back
        $session->msg('d','Sorry, failed to update!');
        redirect('change_password.php', false);
      endif;
    } else {
      // If there were validation errors, display them and redirect back
      $session->msg("d", $errors);
      redirect('change_password.php',false);
    }
  }
?>

<?php 
  // Include the header layout of the page
  include_once('layouts/header.php'); 
?>

<div class="login-page">
    <div class="text-center">
       <!-- Display the title of the page -->
       <h3>Change your password</h3>
     </div>
     
     <!-- Display any session messages (like success or error messages) -->
     <?php echo display_msg($msg); ?>

     <!-- Create the form for password change -->
     <form method="post" action="change_password.php" class="clearfix">
        <div class="form-group">
              <!-- Input field for the new password -->
              <label for="newPassword" class="control-label">New password</label>
              <input type="password" class="form-control" name="new-password" placeholder="New password">
        </div>
        <div class="form-group">
              <!-- Input field for the old password -->
              <label for="oldPassword" class="control-label">Old password</label>
              <input type="password" class="form-control" name="old-password" placeholder="Old password">
        </div>
        <div class="form-group clearfix">
               <!-- Hidden input field to store the user ID -->
               <input type="hidden" name="id" value="<?php echo (int)$user['id'];?>">
               <!-- Submit button for the form -->
                <button type="submit" name="update" class="btn btn-info">Change</button>
        </div>
    </form>
</div>

<?php 
  // Include the footer layout of the page
  include_once('layouts/footer.php'); 
?>
