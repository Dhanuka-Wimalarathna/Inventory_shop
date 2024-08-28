<?php
// Load required files and ensure user is logged in, otherwise redirect to index.php
require_once('includes/load.php');
if (!$session->isUserLoggedIn(true)) {
  redirect('index.php', false);
}
?>

<?php
// Auto-suggestion for product search
$html = '';  // Initialize an empty string for HTML output
if (isset($_POST['product_name']) && strlen($_POST['product_name'])) {
  // Find products by title if product_name is provided and has a length greater than 0
  $products = find_product_by_title($_POST['product_name']);
  if ($products) {
    // If products are found, loop through each product and generate a list item
    foreach ($products as $product):
      $html .= "<li class=\"list-group-item\">";
      $html .= $product['name'];  // Display the product name
      $html .= "</li>";
    endforeach;
  } else {
    // If no products are found, display 'Not found' message
    $html .= '<li onClick=\"fill(\'' . addslashes() . '\')\" class=\"list-group-item\">';
    $html .= 'Not found';
    $html .= "</li>";
  }
  // Encode the HTML output as JSON and return it
  echo json_encode($html);
}
?>

<?php
// Find all product information by title
if (isset($_POST['p_name']) && strlen($_POST['p_name'])) {
  // Sanitize and escape the product title
  $product_title = remove_junk($db->escape($_POST['p_name']));
  // Find all product info by title
  if ($results = find_all_product_info_by_title($product_title)) {
    // If results are found, generate a table row for each product
    foreach ($results as $result) {
      $html .= "<tr>";  // Start a new table row

      $html .= "<td id=\"s_name\">" . $result['name'] . "</td>";  // Product name
      $html .= "<input type=\"hidden\" name=\"s_id\" value=\"{$result['id']}\">";  // Hidden input for product ID

      // Input field for price
      $html .= "<td>";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"price\" value=\"{$result['sale_price']}\">";
      $html .= "</td>";

      // Input field for quantity
      $html .= "<td id=\"s_qty\">";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"quantity\" value=\"1\">";
      $html .= "</td>";

      // Input field for total price
      $html .= "<td>";
      $html .= "<input type=\"text\" class=\"form-control\" name=\"total\" value=\"{$result['sale_price']}\">";
      $html .= "</td>";

      // Input field for date with date picker
      $html .= "<td>";
      $html .= "<input type=\"date\" class=\"form-control datePicker\" name=\"date\" data-date data-date-format=\"yyyy-mm-dd\">";
      $html .= "</td>";

      // Submit button to add the sale
      $html .= "<td>";
      $html .= "<button type=\"submit\" name=\"add_sale\" class=\"btn btn-primary\">Add sale</button>";
      $html .= "</td>";

      $html .= "</tr>";  // End the table row
    }
  } else {
    // If no product is found, display a message
    $html = '<tr><td>product name not registered in database</td></tr>';
  }

  // Encode the HTML output as JSON and return it
  echo json_encode($html);
}
?>