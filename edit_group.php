<?php
  // Set the page title and include the necessary files
  $page_title = 'Edit Group';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);
?>

<?php
  // Find the group by its ID, which is passed through the URL
  $e_group = find_by_id('user_groups', (int)$_GET['id']);
  // If no group is found, display an error message and redirect to the group page
  if(!$e_group){
    $session->msg("d","Missing Group id.");
    redirect('group.php');
  }
?>

<?php
  // Check if the form is submitted
  if(isset($_POST['update'])){

    // Define the required fields for validation
    $req_fields = array('group-name','group-level');
    validate_fields($req_fields);

    // If no validation errors are found, proceed with updating the group
    if(empty($errors)){
        // Sanitize and escape user inputs
        $name = remove_junk($db->escape($_POST['group-name']));
        $level = remove_junk($db->escape($_POST['group-level']));
        $status = remove_junk($db->escape($_POST['status']));

        // Prepare the SQL query to update the group in the database
        $query  = "UPDATE user_groups SET ";
        $query .= "group_name='{$name}', group_level='{$level}', group_status='{$status}'";
        $query .= "WHERE id='{$db->escape($e_group['id'])}'";
        
        // Execute the query and check if the update was successful
        $result = $db->query($query);
        if($result && $db->affected_rows() === 1){
          // Success: Display a success message and redirect back to the edit page
          $session->msg('s',"Group has been updated! ");
          redirect('edit_group.php?id='.(int)$e_group['id'], false);
        } else {
          // Failure: Display an error message and redirect back to the edit page
          $session->msg('d','Sorry, failed to update Group!');
          redirect('edit_group.php?id='.(int)$e_group['id'], false);
        }
    } else {
      // If there are validation errors, display them and redirect back to the edit page
      $session->msg("d", $errors);
      redirect('edit_group.php?id='.(int)$e_group['id'], false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<!-- HTML form for editing the group details -->
<div class="login-page">
    <div class="text-center">
       <h3>Edit Group</h3>
     </div>
     <!-- Display any messages (e.g., success or error messages) -->
     <?php echo display_msg($msg); ?>
     
     <!-- Form to update the group details -->
     <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id'];?>" class="clearfix">
        <!-- Input field for Group Name -->
        <div class="form-group">
              <label for="name" class="control-label">Group Name</label>
              <input type="name" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
        </div>
        <!-- Input field for Group Level -->
        <div class="form-group">
              <label for="level" class="control-label">Group Level</label>
              <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
        </div>
        <!-- Dropdown for Group Status (Active/Deactive) -->
        <div class="form-group">
          <label for="status">Status</label>
              <select class="form-control" name="status">
                <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"';?> value="1">Active</option>
                <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
              </select>
        </div>
        <!-- Submit button to update the group -->
        <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Update</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
