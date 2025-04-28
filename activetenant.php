<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Renter Submission</title>
    <link rel="stylesheet" href="../style/interestform.css">
</head>
<body>

<?php
// Show all PHP errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1) Connect to database
$con = mysqli_connect("db.luddy.indiana.edu", "i308s25_dss4", "lilts0002kicky", "i308s25_dss4");
if (!$con) {
    die("<p> Failed to connect to MySQL: " . mysqli_connect_error() . "</p>");
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Tenants and Rent Payments</title>
</head>
<body>

<h1>Select an Active Tenant</h1>

<form action="activetenants.php" method="POST">
    <label for="renter">Choose a tenant:</label>
    <select name="renter_id" id="renter" required>
        <option value="">--Select a Tenant--</option>
        <?php
        // Fetch active tenants
        $query = "SELECT RenterID, Name_f, Name_l FROM Renters WHERE Renter_Status = 'Active' ORDER BY Name_l, Name_f";
        $result = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            // Keep selected option after form submission
            $selected = (isset($_POST['renter_id']) && $_POST['renter_id'] == $row['RenterID']) ? 'selected' : '';
            echo "<option value='" . $row['RenterID'] . "' $selected>" . htmlspecialchars($row['Name_f']) . " " . htmlspecialchars($row['Name_l']) . "</option>";
        }
        ?>
    </select>
    <br><br>
    <input type="submit" value="View Rent Payments">
</form>

<?php
// Only show rent payments if a tenant was selected
if (isset($_POST['renter_id']) && !empty($_POST['renter_id'])) {
    $renter_id = mysqli_real_escape_string($con, $_POST['renter_id']);

    // Fetch renter details
    $renter_query = "SELECT Name_f, Name_l FROM Renters WHERE RenterID = '$renter_id'";
    $renter_result = mysqli_query($con, $renter_query);
    $renter = mysqli_fetch_assoc($renter_result);

    if ($renter) {
        echo "<h2>Rent Payments for " . htmlspecialchars($renter['Name_f']) . " " . htmlspecialchars($renter['Name_l']) . "</h2>";

        // Fetch rent payments
        $payment_query = "SELECT Transaction_Date, Payment_Amount FROM Rent_Payment WHERE RenterID = '$renter_id' ORDER BY Transaction_Date";
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
            echo "<p>No rent payments found for this tenant.</p>";
        }
    } else {
        echo "<p>Tenant not found.</p>";
    }
}

mysqli_close($con);
?>

</body>
</html>