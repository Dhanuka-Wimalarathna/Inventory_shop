<?php
// Database configuration
define('DB_HOST', 'inventoryshop-server.mysql.database.azure.com');  // Set database host
define('DB_USER', 'cmaafnxmrh');  // Set database user
define('DB_PASS', '4lyKxq7$uC3mzUeW');  // Set database password
define('DB_NAME', 'inventoryshop-database');  // Set database name

// Path to your SSL certificate
$ssl_ca = '/home/site/wwwroot/ca-cert.pem'; // Ensure this path is correct

// Create a new MySQLi object and establish a secure connection using SSL
$conn = mysqli_init();
$conn->ssl_set(null, null, $ssl_ca, null, null);

// Establish connection with SSL
if (!$conn->real_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306, null, MYSQLI_CLIENT_SSL)) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

// Check if the connection is secure
$result = $conn->query("SHOW STATUS LIKE 'Ssl_cipher'");
if (!$result || !$result->fetch_assoc()) {
    echo json_encode(['status' => 'error', 'message' => 'SSL connection could not be established']);
    exit();
}

// ... Your other code here ...

?>
