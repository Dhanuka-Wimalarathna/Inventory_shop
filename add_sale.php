<?php
  // Set the page title
  $page_title = 'Add Sale';
  
  // Include necessary files and initialize the session
  require_once('includes/load.php');
  
  // Check the user's permission level to view this page
  page_require_level(3);
?>

<?php
  // Check if the form is submitted
  if(isset($_POST['add_sale'])){
    // Required fields for the form
    $req_fields = array('s_id','quantity','price','total', 'date');
    
    // Validate the required fields
    validate_fields($req_fields);
    
    // If there are no validation errors
    if(empty($errors)){
      // Sanitize and escape the input data
      $p_id      = $db->escape((int)$_POST['s_id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      $date      = $db->escape($_POST['date']);
      $s_date    = make_date();

      // Insert the sale data into the 'sales' table
      $sql  = "INSERT INTO sales (";
      $sql .= " product_id,qty,price,date";
      $sql .= ") VALUES (";
      $sql .= "'{$p_id}','{$s_qty}','{$s_total}','{$s_date}'";
      $sql .= ")";

      // Execute the query and check if it was successful
      if($db->query($sql)){
        // Update the product quantity in stock
        update_product_qty($s_qty,$p_id);
        
        // Display a success message and redirect
        $session->msg('s',"Sale added. ");
        redirect('add_sale.php', false);
      } else {
        // Display an error message and redirect
        $session->msg('d',' Sorry failed to add!');
        redirect('add_sale.php', false);
      }
    } else {
      // Display validation errors and redirect
      $session->msg("d", $errors);
      redirect('add_sale.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <!-- Display any messages (success or error) -->
    <?php echo display_msg($msg); ?>
    
    <!-- Form to search for a product by name -->
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary">Find It</button>
            </span>
            <input type="text" id="sug_input" class="form-control" name="title"  placeholder="Search for product name">
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Sale Edit</span>
       </strong>
      </div>
      <div class="panel-body">
        <!-- Form to add sale details -->
        <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Item </th>
            <th> Price </th>
            <th> Qty </th>
            <th> Total </th>
            <th> Date</th>
            <th> Action</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
       </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>

