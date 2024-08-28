<?php
  // Set the page title and include the necessary files
  $page_title = 'All Group';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);

  // Retrieve all user groups from the database
  $all_groups = find_all('user_groups');
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <!-- Display any messages (e.g., success or error messages) -->
     <?php echo display_msg($msg); ?>
   </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Groups</span>
        </strong>
        <!-- Button to add a new group -->
        <a href="add_group.php" class="btn btn-info pull-right btn-sm"> Add New Group</a>
      </div>
      <div class="panel-body">
        <!-- Table displaying the list of all user groups -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <!-- Column headers for the group table -->
              <th class="text-center" style="width: 50px;">#</th>
              <th>Group Name</th>
              <th class="text-center" style="width: 20%;">Group Level</th>
              <th class="text-center" style="width: 15%;">Status</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through each group and display its details in a table row -->
            <?php foreach($all_groups as $a_group): ?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td><?php echo remove_junk(ucwords($a_group['group_name']))?></td>
                <td class="text-center">
                  <?php echo remove_junk(ucwords($a_group['group_level']))?>
                </td>
                <td class="text-center">
                  <!-- Display the group status as 'Active' or 'Deactive' -->
                  <?php if($a_group['group_status'] === '1'): ?>
                    <span class="label label-success"><?php echo "Active"; ?></span>
                  <?php else: ?>
                    <span class="label label-danger"><?php echo "Deactive"; ?></span>
                  <?php endif;?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <!-- Button to edit the group -->
                    <a href="edit_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </a>
                    <!-- Button to delete the group -->
                    <a href="delete_group.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                      <i class="glyphicon glyphicon-remove"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
