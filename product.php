<?php
  // Set the page title and include the necessary files
  $page_title = 'All Product';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(1);

  // Retrieve all products by joining related tables (e.g., categories, media)
  $products = join_product_table();
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <!-- Display any messages (e.g., success or error messages) -->
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="pull-right">
          <!-- Button to add a new product -->
          <a href="add_product.php" class="btn btn-primary">Add New</a>
        </div>
      </div>
      <div class="panel-body">
        <!-- Table displaying the list of all products -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <!-- Column headers for the product table -->
              <th class="text-center" style="width: 50px;">#</th>
              <th> Photo</th>
              <th> Product Title </th>
              <th class="text-center" style="width: 10%;"> Categories </th>
              <th class="text-center" style="width: 10%;"> In-Stock </th>
              <th class="text-center" style="width: 10%;"> Buying Price </th>
              <th class="text-center" style="width: 10%;"> Selling Price </th>
              <th class="text-center" style="width: 10%;"> Product Added </th>
              <th class="text-center" style="width: 100px;"> Actions </th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through each product and display its details in a table row -->
            <?php foreach ($products as $product): ?>
            <tr>
              <td class="text-center"><?php echo count_id();?></td>
              <td>
                <!-- Display the product image, or a default image if none is available -->
                <?php if($product['media_id'] === '0'): ?>
                  <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                <?php endif; ?>
              </td>
              <td> <?php echo remove_junk($product['name']); ?></td>
              <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
              <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
              <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
              <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
              <td class="text-center"> <?php echo read_date($product['date']); ?></td>
              <td class="text-center">
                <div class="btn-group">
                  <!-- Button to edit the product -->
                  <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                    <span class="glyphicon glyphicon-edit"></span>
                  </a>
                  <!-- Button to delete the product -->
                  <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
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

<?php include_once('layouts/footer.php'); ?>
