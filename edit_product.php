<?php
// Set the page title and include the necessary files
$page_title = 'Edit product';
require_once('includes/load.php');

// Check if the user has the required permission level to view this page
page_require_level(2);
?>

<?php
// Retrieve the product details based on the ID passed through the URL
$product = find_by_id('products', (int) $_GET['id']);
// Retrieve all categories and photos from the database
$all_categories = find_all('categories');
$all_photo = find_all('media');

// If the product is not found, display an error message and redirect to the product page
if (!$product) {
  $session->msg("d", "Missing product id.");
  redirect('product.php');
}
?>

<?php
// Handle the form submission for updating the product
if (isset($_POST['product'])) {
  // Define the required fields for validation
  $req_fields = array('product-title', 'product-categorie', 'product-quantity', 'buying-price', 'saleing-price');
  validate_fields($req_fields);

  // If no validation errors are found, proceed with updating the product
  if (empty($errors)) {
    // Sanitize and escape user inputs
    $p_name = remove_junk($db->escape($_POST['product-title']));
    $p_cat = (int) $_POST['product-categorie'];
    $p_qty = remove_junk($db->escape($_POST['product-quantity']));
    $p_buy = remove_junk($db->escape($_POST['buying-price']));
    $p_sale = remove_junk($db->escape($_POST['saleing-price']));

    // Check if a photo is selected; if not, set the media_id to '0'
    if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }

    // Prepare the SQL query to update the product in the database
    $query = "UPDATE products SET";
    $query .= " name ='{$p_name}', quantity ='{$p_qty}',";
    $query .= " buy_price ='{$p_buy}', sale_price ='{$p_sale}', categorie_id ='{$p_cat}', media_id='{$media_id}'";
    $query .= " WHERE id ='{$product['id']}'";

    // Execute the query and check if the update was successful
    $result = $db->query($query);
    if ($result && $db->affected_rows() === 1) {
      // Success: Display a success message and redirect to the product page
      $session->msg('s', "Product updated ");
      redirect('product.php', false);
    } else {
      // Failure: Display an error message and redirect back to the edit product page
      $session->msg('d', ' Sorry failed to update!');
      redirect('edit_product.php?id=' . $product['id'], false);
    }
  } else {
    // If there are validation errors, display them and redirect back to the edit product page
    $session->msg("d", $errors);
    redirect('edit_product.php?id=' . $product['id'], false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Display any messages (e.g., success or error messages) -->
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Edit Product</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-7">
        <!-- Form to edit the product details -->
        <form method="post" action="edit_product.php?id=<?php echo (int) $product['id'] ?>">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="glyphicon glyphicon-th-large"></i>
              </span>
              <!-- Input field for the product title -->
              <input type="text" class="form-control" name="product-title"
                value="<?php echo remove_junk($product['name']); ?>">
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-6">
                <!-- Dropdown to select the product category -->
                <select class="form-control" name="product-categorie">
                  <option value=""> Select a category</option>
                  <?php foreach ($all_categories as $cat): ?>
                    <option value="<?php echo (int) $cat['id']; ?>" <?php if ($product['categorie_id'] === $cat['id']):
                         echo "selected"; endif; ?>>
                      <?php echo remove_junk($cat['name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <!-- Dropdown to select the product photo -->
                <select class="form-control" name="product-photo">
                  <option value=""> No image</option>
                  <?php foreach ($all_photo as $photo): ?>
                    <option value="<?php echo (int) $photo['id']; ?>" <?php if ($product['media_id'] === $photo['id']):
                        echo "selected"; endif; ?>>
                      <?php echo $photo['file_name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-4">
                <!-- Input field for the product quantity -->
                <div class="form-group">
                  <label for="qty">Quantity</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity"
                      value="<?php echo remove_junk($product['quantity']); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <!-- Input field for the buying price -->
                <div class="form-group">
                  <label for="qty">Buying price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="buying-price"
                      value="<?php echo remove_junk($product['buy_price']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <!-- Input field for the selling price -->
                <div class="form-group">
                  <label for="qty">Selling price</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-usd"></i>
                    </span>
                    <input type="number" class="form-control" name="saleing-price"
                      value="<?php echo remove_junk($product['sale_price']); ?>">
                    <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Submit button to update the product -->
          <button type="submit" name="product" class="btn btn-danger">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>