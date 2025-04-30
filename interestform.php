<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Renter Submission</title>
    <link rel="stylesheet" href="style/style.css">
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

// mysql -h db.luddy.indiana.edu -u i308s25_team22 --password=waled0502ajuga -D i308s25_team22

// 2) Get and validate form values
$fname    = $_POST['form-fname'];
$mname    = $_POST['form-mname'];
$lname    = $_POST['form-lname'];
$phone    = $_POST['form-phone'];
$email    = $_POST['form-email'];
$rentamt  = $_POST['form-rentamt'];
$movein   = $_POST['form-move-in'];
$bedrooms = $_POST['form-bedrooms'];

// 3) Insert into Renters
$sql1 = "
  INSERT INTO Renters (
    Name_f, Name_m, Name_l,
    Email, Rent_amt, Rent_start_date,
    Room, Renter_Status, Background_check
  ) VALUES (
    '$fname', '$mname', '$lname',
    '$email', '$rentamt', '$movein',
    '$bedrooms', 'FALSE', 'pending'
  )
";

if (mysqli_query($con, $sql1)) {
    echo "<p>New renter added successfully.</p>";
    $newId = mysqli_insert_id($con);

    // 4) Insert phone separately
    $sql2 = "INSERT INTO Renter_Phone (RenterID, Phone, Type)
             VALUES ($newId, '$phone', 'primary')";
    mysqli_query($con, $sql2);
} else {
    die("<p>SQL Error: " . mysqli_error($con) . "</p>");
}

// 5) Display table of all prospective tenants
$result = mysqli_query($con, "
  SELECT
    r.RenterID,
    r.Name_f AS first_name,
    r.Name_m AS middle_name,
    r.Name_l AS last_name,
    p.Phone AS phone,
    r.Email AS email,
    r.Rent_start_date AS move_in_date,
    r.Room AS bedrooms,
    r.Rent_amt AS rent_amount
  FROM Renters r
  LEFT JOIN Renter_Phone p ON r.RenterID = p.RenterID
  WHERE r.Renter_Status = 'FALSE' OR r.Renter_Status IS NULL
  ORDER BY r.Rent_start_date
");
$num_rows = mysqli_num_rows($result);
if ($num_rows > 0) {
    echo "<h2>Current Prospective Tenants</h2>";
    echo "<table border='1' cellpadding='4'>";
    echo "<tr>
            <th>First</th>
            <th>Middle</th>
            <th>Last</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Move In</th>
            <th>Bedrooms</th>
            <th>Rent</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['first_name']}</td>
                <td>{$row['middle_name']}</td>
                <td>{$row['last_name']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['email']}</td>
                <td>{$row['move_in_date']}</td>
                <td>{$row['bedrooms']}</td>
                <td>\${$row['rent_amount']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No prospective tenants found.</p>";
}

mysqli_close($con);
?>

</body>
</html>
