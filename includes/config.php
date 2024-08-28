<?php
// Database configuration
define('DB_HOST', 'inventoryshop-server.mysql.database.azure.com');  // Set database host
define('DB_USER', 'cmaafnxmrh');  // Set database user
define('DB_PASS', '4lyKxq7$uC3mzUeW');  // Set database password
define('DB_NAME', 'inventory_system');  // Set database name

// Path to your SSL certificate
$ssl_ca = '/home/site/wwwroot/ca-cert.pem'; // Ensure this path is correct

// Create a new MySQLi object and establish a connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306);

// Check for any connection errors
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Set SSL parameters
$conn->ssl_set(null, null, $ssl_ca, null, null);

// Check connection again after setting SSL
if ($conn->connect_errno) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed after setting SSL: ' . $conn->connect_error]);
    exit();
} else {
    echo json_encode(['status' => 'success', 'message' => 'Database connected successfully with SSL']);
}

// ... Your other code here ...

?>
