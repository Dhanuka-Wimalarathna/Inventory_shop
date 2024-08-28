<?php
  // Set the page title and include the necessary files
  $page_title = 'Edit categorie';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);
?>

<?php
  // Retrieve the category by its ID, which is passed through the URL
  $categorie = find_by_id('categories', (int)$_GET['id']);
  // If no category is found, display an error message and redirect to the category page
  if(!$categorie){
    $session->msg("d","Missing categorie id.");
    redirect('categorie.php');
  }
?>

<?php
// Check if the form is submitted
if(isset($_POST['edit_cat'])){
  // Define the required fields for validation
  $req_field = array('categorie-name');
  validate_fields($req_field);

  // Sanitize and escape the category name input
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));

  // If no validation errors are found, proceed with updating the category
  if(empty($errors)){
        // Prepare the SQL query to update the category in the database
        $sql = "UPDATE categories SET name='{$cat_name}'";
        $sql .= " WHERE id='{$categorie['id']}'";
        
        // Execute the query and check if the update was successful
        $result = $db->query($sql);
        if($result && $db->affected_rows() === 1) {
          // Success: Display a success message and redirect to the category page
          $session->msg("s", "Successfully updated Categorie");
          redirect('categorie.php', false);
        } else {
          // Failure: Display an error message and redirect to the category page
          $session->msg("d", "Sorry! Failed to Update");
          redirect('categorie.php', false);
        }
  } else {
    // If there are validation errors, display them and redirect to the category page
    $session->msg("d", $errors);
    redirect('categorie.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<!-- HTML structure for the edit category form -->
<div class="row">
   <div class="col-md-12">
     <!-- Display any messages (e.g., success or error messages) -->
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <!-- Display the name of the category being edited -->
           <span>Editing <?php echo remove_junk(ucfirst($categorie['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <!-- Form to update the category name -->
         <form method="post" action="edit_categorie.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <!-- Input field for the category name -->
               <input type="text" class="form-control" name="categorie-name" value="<?php echo remove_junk(ucfirst($categorie['name']));?>">
           </div>
           <!-- Submit button to update the category -->
           <button type="submit" name="edit_cat" class="btn btn-primary">Update categorie</button>
       </form>
       </div>
     </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>

