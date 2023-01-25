<!DOCTYPE HTML>
<?php
include "checksession.php";
//checkUser();
//loginStatus(); 
?>
<html><head><title>Booking Details View</title> </head>
<body>

<?php
include "config.php"; //load in any variables
$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
 echo "<h2>Invalid Booking ID</h2>"; //simple error feedback
 exit;
} 

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 
	'SELECT * 
	FROM room
	INNER JOIN booking 
	ON room.roomID = booking.roomID
	INNER JOIN customer 
	ON booking.customerID = customer.customerID
	WHERE bookingID='.$id;

$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>

<h1>Booking Details View</h1>
<h2><a href="currentbookings.php">[Return to the booking listing]</a><a href="index.php">[Return to the main page]</a></h2>
 
 <?php
//makes sure we have the Booking
if($rowcount > 0)
{  
   echo "<fieldset><legend>Room detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
   echo "<dt>Checkin date:</dt><dd>".$row['checkin_date']."</dd>".PHP_EOL;
   echo "<dt>Checkout date:</dt><dd>".$row['checkout_date']."</dd>".PHP_EOL;
   echo "<dt>Contact number:</dt><dd>".$row['contact_number']."</dd>".PHP_EOL;
   echo "<dt>Extras:</dt><dd>".$row['booking_extras']."</dd>".PHP_EOL;
   echo "<dt>Room review:</dt><dd>".$row['room_review']."</dd>".PHP_EOL;
   

   echo '</dl></fieldset>'.PHP_EOL;  
}
else
{
	echo "<h2>No booking found!</h2>"; //suitable feedback
}
mysqli_free_result($result); //free any memory used by the query
mysqli_close($db_connection); //close the connection once done
?> 
 </table>
 <br>
 <br>
 <br>
 <a href='privacy.html'>[Privacy]</a>


</body>

</html>