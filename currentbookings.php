<!DOCTYPE HTML>
<html><head><title>Current bookings</title> </head>
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

//prepare a query and send it to the server
$query = 
	'SELECT * 
	FROM room
	INNER JOIN booking 
	ON room.roomID = booking.roomID
	INNER JOIN customer 
	ON booking.customerID = customer.customerID
	ORDER BY room.roomname';
$result = mysqli_query($db_connection, $query);
$rowcount = mysqli_num_rows($result); 
?>
 
<h1>Current bookings</h1>
<h2><a href='makeabooking.php'>[Make a booking]</a><a href="index.php">[Return to main page]</a></h2>
<table border= "1">
<thead><tr><th>Booking (room, dates)</th><th>Customer</th><th>Action</th></tr></thead>
<?php

//makes sure we have rooms
if ($rowcount > 0) {  
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookingID'];	
	  echo '<tr><td>'.$row['roomname'].", ".$row['checkin_date'].", ".$row['checkout_date'].'</td><td>'.$row['lastname'].", ".$row['firstname'].'</td>';
	  echo '<td><a href="bookingdetailsview.php?id='.$id.'">[view]</a>';
	  echo '<a href="editabooking.php?id='.$id.'">[edit]</a>';
	  echo '<a href="bookingpreviewbeforedeletion.php?id='.$id.'">[delete]</a></td>';
      echo '</tr>';
   }
} else echo "<h2>No bookings found!</h2>"; //suitable feedback

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