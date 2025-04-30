<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Connect to database
$con = mysqli_connect("db.luddy.indiana.edu", "username", "password", "database_name");
if (!$con) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

// 2) Fetch list of houses
$listings_sql    = "SELECT HouseID, Address_C, Address_Sta, Address_Str FROM House";
$listings_result = mysqli_query($con, $listings_sql);

// 3) If form submitted, fetch payments
$payments_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $listing_id = intval($_POST['listing_id']);
    $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
    $end_date   = mysqli_real_escape_string($con, $_POST['end_date']);

    $payments_sql = "
      SELECT 
        Transaction_Date, 
        Payment_Amount, 
        RenterID, 
        HouseID
      FROM Rent_Payment
      WHERE HouseID = {$listing_id}
        AND Transaction_Date BETWEEN '{$start_date}' AND '{$end_date}'
      ORDER BY Transaction_Date
    ";
    $payments_result = mysqli_query($con, $payments_sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Rented Listings</title>
  <link rel="stylesheet" href="style/rentedlistings.css">
</head>
<body>
  <nav>
    <a href="index.php">Home</a>
    <a href="rentedlistings.php">Rented Listings</a>
    <a href="interestform.html">Interest Form</a>
    <a href="listofrenters.html">List of Renters</a>
    <a href="activetenants.html">Active Tenants</a>
    <a href="availablelistings.html">Available Listings</a>
    <a href="homeownersignup.html">List a Property</a>
    <a href="contactlisthomeowner.html">Homeowner Contact</a>
  </nav>

  <h1>RentMatch</h1>
  <h2>Rent Made Simple</h2>
  <h3>Rented Listings</h3>

  <form method="POST" action="">
    <label for="listing_id">Select Property:</label>
    <select name="listing_id" id="listing_id" required>
      <option value="" disabled selected>— Choose a property —</option>
      <?php while($row = mysqli_fetch_assoc($listings_result)): ?>
        <option value="<?= $row['HouseID'] ?>">
          <?= htmlspecialchars("{$row['Address_C']} {$row['Address_Sta']} {$row['Address_Str']}") ?>
        </option>
      <?php endwhile; ?>
    </select>

    <br>
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" required>

    <br>
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" id="end_date" required>

    <br>
    <input type="submit" value="Show Payments">
  </form>

  <?php if ($payments_result): ?>
    <?php if (mysqli_num_rows($payments_result) > 0): ?>
      <div class="form-box">
        <h3>Payments for Selected Property</h3>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Payment Amount</th>
              <th>Renter ID</th>
              <th>Property ID</th>
            </tr>
          </thead>
          <tbody>
            <?php while($p = mysqli_fetch_assoc($payments_result)): ?>
              <tr>
                <td><?= $p['Transaction_Date'] ?></td>
                <td>$<?= number_format($p['Payment_Amount'], 2) ?></td>
                <td><?= $p['RenterID'] ?></td>
                <td><?= $p['HouseID'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>No payments found in that date range.</p>
    <?php endif; ?>
  <?php endif; ?>

</body>
</html>
<?php
mysqli_close($con);
?>