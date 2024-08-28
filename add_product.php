<?php
  // Set the page title
  $page_title = 'Add Product';
  
  // Include the necessary files for loading functions and checking permissions
  require_once('includes/load.php');
  
  // Ensure the user has the appropriate permission level to view this page
  page_require_level(2);
  
  // Fetch all categories and media records from the database
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>

<?php
  // Check if the form has been submitted
  if(isset($_POST['add_product'])){
    
    // Define required fields for validation
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price');
    
    // Validate the required fields
    validate_fields($req_fields);
    
    // If there are no validation errors, proceed to insert the product
    if(empty($errors)){
      // Sanitize and escape the input data
      $p_name  = remove_junk($db->escape($_POST['product-title']));
      $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
      $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
      $p_buy   = remove_junk($db->escape($_POST['buying-price']));
      $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
      
      // Handle product photo, set to '0' if no photo is selected
      $media_id = is_null($_POST['product-photo']) || $_POST['product-photo'] === "" ? '0' : remove_junk($db->escape($_POST['product-photo']));
      
      // Get the current date
      $date    = make_date();
      
      // Prepare the SQL query to insert the product into the database
      $query  = "INSERT INTO products (";
      $query .=" name,quantity,buy_price,sale_price,categorie_id,media_id,date";
      $query .=") VALUES (";
      $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}'";
      $query .=") ON DUPLICATE KEY UPDATE name='{$p_name}'";
      
      // Execute the query and check if the product was successfully added
      if($db->query($query)){
        // If successful, set a success message and redirect to the add product page
        $session->msg('s',"Product added ");
        redirect('add_product.php', false);
      } else {
        // If the query failed, set an error message and redirect to the products page
        $session->msg('d',' Sorry failed to add!');
        redirect('product.php', false);
      }
    } else {
      // If there are validation errors, set an error message and redirect back to the add product page
      $session->msg("d", $errors);
      redirect('add_product.php',false);
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<!-- Display any session messages -->
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<!-- Form to add a new product -->
<div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              
              <!-- Input for product title -->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Product Title">
               </div>
              </div>
              
              <!-- Dropdowns for product category and photo -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Select Product Photo</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Inputs for product quantity, buying price, and selling price -->
              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              
              <!-- Submit button to add the product -->
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>

