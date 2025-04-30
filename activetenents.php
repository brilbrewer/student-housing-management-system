<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Payments</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<?php
// Show all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to the database
$con = mysqli_connect("db.luddy.indiana.edu", "i308s25_team22", "waled0502ajuga", "i308s25_team22");
if (!$con) {
    die("<p>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>");
}

// Check if a renter and date range have been selected
if (isset($_POST['renter_id']) && !empty($_POST['renter_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $renter_id = mysqli_real_escape_string($con, $_POST['renter_id']);
    $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($con, $_POST['end_date']);

    // Query to get renter details
    $renter_query = "SELECT Name_f, Name_l FROM Renters WHERE RenterID = '$renter_id'";
    $renter_result = mysqli_query($con, $renter_query);
    $renter = mysqli_fetch_assoc($renter_result);

    if ($renter) {
        echo "<h2>Rent Payments for " . htmlspecialchars($renter['Name_f']) . " " . htmlspecialchars($renter['Name_l']) . "</h2>";

        // Fetch rent payments for the selected renter and within the date range
        $payment_query = "SELECT Transaction_Date, Payment_Amount FROM Rent_Payment WHERE RenterID = '$renter_id' AND Transaction_Date BETWEEN '$start_date' AND '$end_date' ORDER BY Transaction_Date";
        $payment_result = mysqli_query($con, $payment_query);

        if (mysqli_num_rows($payment_result) > 0) {
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>Transaction Date</th><th>Payment Amount</th></tr>";

            while ($payment = mysqli_fetch_assoc($payment_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($payment['Transaction_Date']) . "</td>";
                echo "<td>$" . htmlspecialchars(number_format($payment['Payment_Amount'], 2)) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No rent payments found for this tenant in the selected date range.</p>";
        }
    } else {
        echo "<p>Tenant not found.</p>";
    }
} else {
    echo "<p>Please select a tenant and date range to view rent payments.</p>";
}

mysqli_close($con);
?>

</body>
</html>
