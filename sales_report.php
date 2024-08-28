<?php
// Set the page title
$page_title = 'Sale Report';

// Include necessary files and functions
require_once('includes/load.php');

// Check what level of permission the user has to view this page
page_require_level(3);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <!-- Display any messages (errors, success) -->
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
        <!-- Panel heading (optional) -->
      </div>
      <div class="panel-body">
          <!-- Form for selecting date range to generate the sales report -->
          <form class="clearfix" method="post" action="sale_report_process.php">
            <div class="form-group">
              <label class="form-label">Date Range</label>
                <div class="input-group">
                  <!-- Input field for start date -->
                  <input type="text" class="datepicker form-control" name="start-date" placeholder="From">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <!-- Input field for end date -->
                  <input type="text" class="datepicker form-control" name="end-date" placeholder="To">
                </div>
            </div>
            <div class="form-group">
                 <!-- Button to submit the form and generate the report -->
                 <button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

