<?php
// Set the page title
$page_title = 'All categories';

// Include necessary files and scripts
require_once('includes/load.php');

// Check what level user has permission to view this page
page_require_level(1);

// Fetch all categories from the database
$all_categories = find_all('categories');
?>
<?php
// Check if the form to add a new category has been submitted
if (isset($_POST['add_cat'])) {
  // Define the required field for the form submission
  $req_field = array('categorie-name');

  // Validate the required field
  validate_fields($req_field);

  // Clean and escape the category name to prevent SQL injection
  $cat_name = remove_junk($db->escape($_POST['categorie-name']));

  // If no errors, proceed to insert the new category into the database
  if (empty($errors)) {
    $sql = "INSERT INTO categories (name)";
    $sql .= " VALUES ('{$cat_name}')";

    // Execute the query and check if the insertion was successful
    if ($db->query($sql)) {
      $session->msg("s", "Successfully Added New Category");
      // Redirect to the category page if successful
      redirect('categorie.php', false);
    } else {
      $session->msg("d", "Sorry Failed to insert.");
      // Redirect to the category page if failed
      redirect('categorie.php', false);
    }
  } else {
    // If there are validation errors, display them and redirect
    $session->msg("d", $errors);
    redirect('categorie.php', false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Display any session messages (success or error) -->
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Add New Category</span>
        </strong>
      </div>
      <div class="panel-body">
        <!-- Form to add a new category -->
        <form method="post" action="categorie.php">
          <div class="form-group">
            <!-- Input field for category name -->
            <input type="text" class="form-control" name="categorie-name" placeholder="Category Name">
          </div>
          <!-- Submit button to add the category -->
          <button type="submit" name="add_cat" class="btn btn-primary">Add Category</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Categories</span>
        </strong>
      </div>
      <div class="panel-body">
        <!-- Table displaying all categories -->
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <!-- Table headers for category ID, name, and actions -->
              <th class="text-center" style="width: 50px;">#</th>
              <th>Categories</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through all categories and display each one in a table row -->
            <?php foreach ($all_categories as $cat): ?>
              <tr>
                <!-- Display category ID -->
                <td class="text-center"><?php echo count_id(); ?></td>

                <!-- Display category name -->
                <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>

                <!-- Action buttons for editing or deleting the category -->
                <td class="text-center">
                  <div class="btn-group">
                    <!-- Edit button -->
                    <a href="edit_categorie.php?id=<?php echo (int) $cat['id']; ?>" class="btn btn-xs btn-warning"
                      data-toggle="tooltip" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>

                    <!-- Delete button -->
                    <a href="delete_categorie.php?id=<?php echo (int) $cat['id']; ?>" class="btn btn-xs btn-danger"
                      data-toggle="tooltip" title="Remove">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Include the footer -->
<?php include_once('layouts/footer.php'); ?>