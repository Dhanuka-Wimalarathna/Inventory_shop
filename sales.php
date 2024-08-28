<?php
  // Set the page title
  $page_title = 'All sale';

  // Include necessary files and functions
  require_once('includes/load.php');

  // Check what level of permission the user has to view this page
  page_require_level(1);
?>

<?php
// Retrieve all sales data from the database
$sales = find_all_sale();
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <!-- Display any messages (errors, success) -->
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sales</span>
        </strong>
        <div class="pull-right">
          <!-- Button to add a new sale -->
          <a href="add_sale.php" class="btn btn-primary">Add sale</a>
        </div>
      </div>
      <div class="panel-body">
        <!-- Table displaying all sales -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product name </th>
              <th class="text-center" style="width: 15%;"> Quantity</th>
              <th class="text-center" style="width: 15%;"> Total </th>
              <th class="text-center" style="width: 15%;"> Date </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through each sale and display its details -->
            <?php foreach ($sales as $sale): ?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td><?php echo remove_junk($sale['name']); ?></td>
              <td class="text-center"><?php echo (int)$sale['qty']; ?></td>
              <td class="text-center"><?php echo remove_junk($sale['price']); ?></td>
              <td class="text-center"><?php echo $sale['date']; ?></td>
              <td class="text-center">
                <!-- Buttons for editing or deleting a sale -->
                <div class="btn-group">
                   <a href="edit_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-warning btn-xs"  title="Edit" data-toggle="tooltip">
                     <span class="glyphicon glyphicon-edit"></span>
                   </a>
                   <a href="delete_sale.php?id=<?php echo (int)$sale['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                     <span class="glyphicon glyphicon-trash"></span>
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
