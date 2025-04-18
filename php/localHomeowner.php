
<?php
$con=mysqli_connect("db.luddy.indiana.edu", "username", "password", "database");
// Check connection
if (!$con)
{ die("Failed to connect to MySQL: " . mysqli_connect_error()); }
else
{ echo "Established Database Connection" ;}
;
// Assign input to Variables
$cname = $_POST['form-cname'];
$caddress = $_POST['form-caddress'];
$cphone = $_POST['form-cphone'];
// Run Query
$sql="INSERT INTO customer(name,address,phone) VALUES ('$cname','$caddress','$cphone')";
if (mysqli_query($con, $sql))
{echo "1 record added";}
else
{die('SQL Error: ' . mysqli_error($con)); }
// Close the Connection
mysqli_close($con);
?