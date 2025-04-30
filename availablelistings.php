<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Listings</title>
    <link rel="stylesheet" href="style/available-listings.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="rentedlistings.html">Rented Listings</a>
        <a href="interestform.html">Interest Form</a>
        <a href="listofrenters.html">List of Renters</a>
        <a href="activetenent.html">Active Tenants</a>
        <a href="availablelistings.html">Available Listings</a>
        <a href="homeownersignup.html">List a Property</a>
        <a href="contactlisthomeowner.html">Homeowner Contact</a>
    </nav>

    <h1>RentMatch</h1>
    <h2>Available Listings For You</h2>

    <form action="availablelistings.php" method="post">
        <label for="bedroom-sort">Sort by Bedrooms:</label>
        <select id="bedroom-sort" name="bedrooms">
            <option value="">Choose number of rooms</option>
            <option value="1">1 Bedroom</option>
            <option value="2">2 Bedrooms</option>
            <option value="3">3 Bedrooms</option>
            <option value="4">4 Bedrooms</option>
        </select>
        <button type="submit">Submit</button>
    </form>

    <div class="listings-container">
        <?php
        $con = mysqli_connect("db.luddy.indiana.edu", "i308s25_team22", "waled0502ajuga", "i308s25_team22");

        if (!$con) {
            echo "<p>Failed to connect to database: " . mysqli_connect_error() . "</p>";
            exit();
        }

        $selectedBedrooms = isset($_POST['bedrooms']) ? intval($_POST['bedrooms']) : 0;

        $query = "SELECT Address_Str, Address_C, Address_Sta, BedroomNum, Amenities, Details 
                  FROM House";

        if ($selectedBedrooms > 0) {
            $query .= " WHERE BedroomNum = $selectedBedrooms";
        }

        $result = mysqli_query($con, $query);

        if (!$result) {
            echo "<p>Query failed: " . mysqli_error($con) . "</p>";
        } elseif (mysqli_num_rows($result) === 0) {
            echo "<p>No listings found for that bedroom count.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='listing-box'>
                        <h3>" . htmlspecialchars($row['BedroomNum']) . " Bedroom Listing</h3>
                        <p><strong>Address:</strong> " . htmlspecialchars($row['Address_Str'] . ', ' . $row['Address_C'] . ', ' . $row['Address_Sta']) . "</p>
                        <p><strong>Amenities:</strong> " . nl2br(htmlspecialchars($row['Amenities'])) . "</p>
                        <p><strong>Details:</strong> " . htmlspecialchars($row['Details']) . "</p>
                      </div>";
            }
        }

        mysqli_free_result($result);
        mysqli_close($con);
        ?>
    </div>
</body>
</html>