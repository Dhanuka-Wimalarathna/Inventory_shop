<?php
  // Set the page title and include the necessary files
  $page_title = 'Edit Account';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(3);
?>

<?php
// Handle the user image update
if(isset($_POST['submit'])) {
  // Create a new Media object
  $photo = new Media();
  $user_id = (int)$_POST['user_id']; // Get the user ID from the form
  $photo->upload($_FILES['file_upload']); // Upload the file

  // Process the uploaded image for the user
  if($photo->process_user($user_id)){
    // Success: Display a success message and redirect to the edit account page
    $session->msg('s','Photo has been uploaded.');
    redirect('edit_account.php');
  } else {
    // Failure: Display an error message and redirect to the edit account page
    $session->msg('d', join($photo->errors));
    redirect('edit_account.php');
  }
}
?>

<?php
// Handle the update of other user information
if(isset($_POST['update'])){
  // Define the required fields for validation
  $req_fields = array('name','username');
  validate_fields($req_fields);

  // If no validation errors are found, proceed with updating the user info
  if(empty($errors)){
    $id = (int)$_SESSION['user_id']; // Get the current user ID
    $name = remove_junk($db->escape($_POST['name'])); // Sanitize and escape the name input
    $username = remove_junk($db->escape($_POST['username'])); // Sanitize and escape the username input

    // Prepare the SQL query to update the user information in the database
    $sql = "UPDATE users SET name ='{$name}', username ='{$username}' WHERE id='{$id}'";
    $result = $db->query($sql);

    // Check if the update was successful
    if($result && $db->affected_rows() === 1){
      // Success: Display a success message and redirect to the edit account page
      $session->msg('s',"Account updated");
      redirect('edit_account.php', false);
    } else {
      // Failure: Display an error message and redirect to the edit account page
      $session->msg('d','Sorry, failed to update!');
      redirect('edit_account.php', false);
    }
  } else {
    // If there are validation errors, display them and redirect to the edit account page
    $session->msg("d", $errors);
    redirect('edit_account.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Display any messages (e.g., success or error messages) -->
    <?php echo display_msg($msg); ?>
  </div>

  <!-- Section for changing the user photo -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-heading clearfix">
          <span class="glyphicon glyphicon-camera"></span>
          <span>Change My Photo</span>
        </div>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <!-- Display the current user photo -->
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user['image'];?>" alt="">
          </div>
          <div class="col-md-8">
            <!-- Form to upload a new photo -->
            <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <!-- File input for selecting a new photo -->
                <input type="file" name="file_upload" multiple="multiple" class="btn btn-default btn-file"/>
              </div>
              <div class="form-group">
                <!-- Hidden input to pass the user ID -->
                <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
                <!-- Submit button to change the photo -->
                <button type="submit" name="submit" class="btn btn-warning">Change</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section for editing user account information -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Edit My Account</span>
      </div>
      <div class="panel-body">
        <!-- Form to update the user information -->
        <form method="post" action="edit_account.php?id=<?php echo (int)$user['id'];?>" class="clearfix">
          <!-- Input field for the user's name -->
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['name'])); ?>">
          </div>
          <!-- Input field for the user's username -->
          <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($user['username'])); ?>">
          </div>
          <div class="form-group clearfix">
            <!-- Link to change the password -->
            <a href="change_password.php" title="change password" class="btn btn-danger pull-right">Change Password</a>
            <!-- Submit button to update the user information -->
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

