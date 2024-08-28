<?php
  // Set the page title and include the necessary files
  $page_title = 'Edit sale';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(3);
?>

<?php
  // Retrieve the sale details based on the ID passed through the URL
  $sale = find_by_id('sales', (int)$_GET['id']);
  
  // If the sale is not found, display an error message and redirect to the sales page
  if(!$sale){
    $session->msg("d","Missing product id.");
    redirect('sales.php');
  }
?>

<?php
  // Retrieve the product associated with the sale
  $product = find_by_id('products', $sale['product_id']);
?>

<?php
  // Handle the form submission for updating the sale
  if(isset($_POST['update_sale'])){
    // Define the required fields for validation
    $req_fields = array('title','quantity','price','total', 'date');
    validate_fields($req_fields);

    // If no validation errors are found, proceed with updating the sale
    if(empty($errors)){
      // Sanitize and escape user inputs
      $p_id      = $db->escape((int)$product['id']);
      $s_qty     = $db->escape((int)$_POST['quantity']);
      $s_total   = $db->escape($_POST['total']);
      $date      = $db->escape($_POST['date']);
      $s_date    = date("Y-m-d", strtotime($date));

      // Prepare the SQL query to update the sale in the database
      $sql  = "UPDATE sales SET";
      $sql .= " product_id= '{$p_id}', qty={$s_qty}, price='{$s_total}', date='{$s_date}'";
      $sql .= " WHERE id ='{$sale['id']}'";

      // Execute the query and check if the update was successful
      $result = $db->query($sql);
      if($result && $db->affected_rows() === 1){
        // Update the product quantity and display a success message
        update_product_qty($s_qty, $p_id);
        $session->msg('s',"Sale updated.");
        redirect('edit_sale.php?id='.$sale['id'], false);
      } else {
        // Failure: Display an error message and redirect to the sales page
        $session->msg('d',' Sorry failed to update!');
        redirect('sales.php', false);
      }
    } else {
      // If there are validation errors, display them and redirect back to the edit sale page
      $session->msg("d", $errors);
      redirect('edit_sale.php?id='.(int)$sale['id'], false);
    }
  }
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
    <div class="panel">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Sales</span>
        </strong>
        <div class="pull-right">
          <!-- Button to view all sales -->
          <a href="sales.php" class="btn btn-primary">Show all sales</a>
        </div>
      </div>
      <div class="panel-body">
        <!-- Table displaying the sale details for editing -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <!-- Column headers for the sale edit form -->
              <th> Product title </th>
              <th> Qty </th>
              <th> Price </th>
              <th> Total </th>
              <th> Date</th>
              <th> Action</th>
            </tr>
          </thead>
          <tbody id="product_info">
            <tr>
              <!-- Form to update the sale details -->
              <form method="post" action="edit_sale.php?id=<?php echo (int)$sale['id']; ?>">
                <td id="s_name">
                  <!-- Input field for the product title -->
                  <input type="text" class="form-control" id="sug_input" name="title" value="<?php echo remove_junk($product['name']); ?>">
                  <div id="result" class="list-group"></div>
                </td>
                <td id="s_qty">
                  <!-- Input field for the quantity sold -->
                  <input type="text" class="form-control" name="quantity" value="<?php echo (int)$sale['qty']; ?>">
                </td>
                <td id="s_price">
                  <!-- Input field for the product price -->
                  <input type="text" class="form-control" name="price" value="<?php echo remove_junk($product['sale_price']); ?>" >
                </td>
                <td>
                  <!-- Input field for the total sale amount -->
                  <input type="text" class="form-control" name="total" value="<?php echo remove_junk($sale['price']); ?>">
                </td>
                <td id="s_date">
                  <!-- Input field for the sale date -->
                  <input type="date" class="form-control datepicker" name="date" data-date-format="" value="<?php echo remove_junk($sale['date']); ?>">
                </td>
                <td>
                  <!-- Submit button to update the sale -->
                  <button type="submit" name="update_sale" class="btn btn-primary">Update sale</button>
                </td>
              </form>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
