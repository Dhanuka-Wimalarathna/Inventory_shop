<?php
// Set the page title
$page_title = 'Sales Report';
$results = ''; // Initialize the results variable
require_once('includes/load.php');

// Check what level of permission the user has to view this page
page_require_level(1);

// Function to export sales report data to a CSV file
function export_to_csv($results)
{
    // Set the content type and disposition for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sales_report_' . date('Ymd') . '.csv"');

    // Open a file handle to the output stream
    $output = fopen('php://output', 'w');

    // Write the header row to the CSV
    fputcsv($output, ['Date', 'Product Title', 'Buying Price', 'Selling Price', 'Total Qty', 'TOTAL']);

    // Loop through the results and write each row to the CSV
    foreach ($results as $result) {
        fputcsv($output, [
            $result['date'],
            ucfirst($result['name']),
            $result['buy_price'],
            $result['sale_price'],
            $result['total_sales'],
            $result['total_saleing_price']
        ]);
    }

    // Write the grand total and profit rows to the CSV
    fputcsv($output, ['', '', '', 'Grand Total', '', number_format(total_price($results)[0], 2)]);
    fputcsv($output, ['', '', '', 'Profit', '', number_format(total_price($results)[1], 2)]);

    // Close the file handle and exit
    fclose($output);
    exit;
}

// Check if the 'export_csv' parameter is set
if (isset($_GET['export_csv'])) {
    // If there are results, export them to CSV
    if (!empty($results)) {
        export_to_csv($results);
    } else {
        // If no results, show an error message and redirect
        $session->msg("d", "No sales data available for export.");
        redirect('sales_report.php', false);
    }
}

// Existing report generation code
if (isset($_POST['submit'])) {
    // Define required fields for date range
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    // If no errors, fetch sales data for the specified date range
    if (empty($errors)) {
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        $results = find_sale_by_dates($start_date, $end_date);
    } else {
        // If errors, show error message and redirect
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    }
} else {
    // If no dates selected, show an error message and redirect
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}
?>

<!doctype html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
    <style>
        /* Styling for print media */
        @media print {

            html,
            body {
                font-size: 9.5pt;
                margin: 0;
                padding: 0;
            }

            .page-break {
                page-break-before: always;
                width: auto;
                margin: auto;
            }
        }

        .page-break {
            width: 980px;
            margin: 0 auto;
        }

        .sale-head {
            margin: 40px 0;
            text-align: center;
        }

        .sale-head h1,
        .sale-head strong {
            padding: 10px 20px;
            display: block;
        }

        .sale-head h1 {
            margin: 0;
            border-bottom: 1px solid #212121;
        }

        .table>thead:first-child>tr:first-child>th {
            border-top: 1px solid #000;
        }

        table thead tr th {
            text-align: center;
            border: 1px solid #ededed;
        }

        table tbody tr td {
            vertical-align: middle;
        }

        .sale-head,
        table.table thead tr th,
        table tbody tr td,
        table tfoot tr td {
            border: 1px solid #212121;
            white-space: nowrap;
        }

        .sale-head h1,
        table thead tr th,
        table tfoot tr td {
            background-color: #f8f8f8;
        }

        tfoot {
            color: #000;
            text-transform: uppercase;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <?php if ($results): ?>
        <div class="page-break">
            <div class="sale-head">
                <h1>Inventory Management System - Sales Report</h1>
                <strong><?php if (isset($start_date)) {
                    echo $start_date;
                } ?> TILL DATE
                    <?php if (isset($end_date)) {
                        echo $end_date;
                    } ?></strong>
                <!-- Add Export to CSV Button -->
                <a href="?export_csv=true" class="btn btn-success">Export to CSV</a>
            </div>
            <table class="table table-border">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Title</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Total Qty</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through results and display each sale -->
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?php echo remove_junk($result['date']); ?></td>
                            <td class="desc">
                                <h6><?php echo remove_junk(ucfirst($result['name'])); ?></h6>
                            </td>
                            <td class="text-right"><?php echo remove_junk($result['buy_price']); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['sale_price']); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['total_sales']); ?></td>
                            <td class="text-right"><?php echo remove_junk($result['total_saleing_price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <!-- Display grand total and profit in the footer -->
                    <tr class="text-right">
                        <td colspan="4"></td>
                        <td colspan="1">Grand Total</td>
                        <td>$<?php echo number_format(total_price($results)[0], 2); ?></td>
                    </tr>
                    <tr class="text-right">
                        <td colspan="4"></td>
                        <td colspan="1">Profit</td>
                        <td>$<?php echo number_format(total_price($results)[1], 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <!-- If no results, show an error message and redirect -->
        <?php $session->msg("d", "Sorry no sales have been found."); ?>
        <?php redirect('sales_report.php', false); ?>
    <?php endif; ?>
</body>

</html>

<?php
// Close the database connection if it's open
if (isset($db)) {
    $db->db_disconnect();
}
?>