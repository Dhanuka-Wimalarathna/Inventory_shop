<?php
// Set the page title and include the necessary files
$page_title = 'Daily Sales';
require_once('includes/load.php');

// Check if the user has the required permission level to view this page
page_require_level(3);
?>

<?php
// Get the current year and month
$year = date('Y');
$month = date('m');
$data = date('d');

// Retrieve the daily sales data for the current year and month
$sales = dailySales($year, $month,$date);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
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
          <span>Daily Sales</span>
        </strong>
      </div>
      <div class="panel-body">
        <!-- Table displaying the daily sales data -->
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <!-- Column headers for the sales table -->
              <th class="text-center" style="width: 50px;">#</th>
              <th> Product name </th>
              <th class="text-center" style="width: 15%;"> Quantity sold</th>
              <th class="text-center" style="width: 15%;"> Total </th>
              <th class="text-center" style="width: 15%;"> Date </th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through each sale and display its details in a table row -->
            <?php foreach ($sales as $sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($sale['name']); ?></td>
                <td class="text-center"><?php echo (int) $sale['qty']; ?></td>
                <td class="text-center"><?php echo remove_junk($sale['total_saleing_price']); ?></td>
                <td class="text-center"><?php echo $sale['date']; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>