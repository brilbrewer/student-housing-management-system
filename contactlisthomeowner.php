<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Homeowners</title>
    <link rel="stylesheet" href="style/contactlisthomeowner.css">
</head>
<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="rentedlistings.html">Rented Listings</a>
        <a href="interestform.html">Interest Form</a>
        <a href="listofrenters.html">List of Renters</a>
        <a href="activetenent.html">Active Tenants</a>
        <a href="avaliablelistings.html">Avaliable Listings</a>
        <a href="homeownersignup.html">List a Property</a>
        <a href="contactlisthomeowner.html">Homeowner Contact</a>
    </nav>
    <h1>RentMatch</h1>
    <h2>Homeowner contact list</h2>
    <?php
    $con=mysqli_connect("db.luddy.indiana.edu","i308s25_team22","waled0502ajuga", "i308s25_team22");
    if (!$con)
            {die("Failed to connect to MySQL: " . mysqli_connect_error()); }
    ;
    $query = "SELECT CONCAT(lh.Name_f, ' ', lh.Name_l) as 'Name', hp.Phone as 'Phone', CONCAT(h.Address_Str, ', ', h.Address_C, ', ', h.Address_Sta) as 'Address'
    From Local_Homeowners as lh
    Join Homeowner_Phone as hp on hp.HomeownerID = lh.HomeownerID
    Join House as h on hp.HomeownerID = h.HomeownerID";

    $result = mysqli_query($con,$query) or die("Query Failed!");
    
    while ($row = mysqli_fetch_assoc($result))
        {echo "<h3>" . $row['Name'] . "</h3>
                <p> Phone: " . $row['Phone'] . "</p>
                <p> Listings: " . $row['Address'] . "</p>";}
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>
</body>
</html>