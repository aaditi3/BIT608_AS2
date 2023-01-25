<!DOCTYPE HTML>
<html><head><title>Edit a booking</title> </head>
<body>
<?php
include "config.php"; //load in any variables
include "cleaninput.php";

$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
$error=0;
if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
  exit; //stop processing the page further
};

//retrieve the bookingid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid booking ID</h2>"; //simple error feedback
        exit;
    } 
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
//validate incoming data - only the first field is done for you in this example - rest is up to you to do
    
//bookingID (sent via a form it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid room ID '; //append error message
       $id = 0;  
    } 

//roomID
	   $roomID = cleanInput($_POST['roomID']); 
//checkin_date
       $checkin_date = cleanInput($_POST['checkin_date']); 
//checkout_date
       $checkout_date = cleanInput($_POST['checkout_date']);        
//contact_number
       $contact_number = cleanInput($_POST['contact_number']);         
//booking_extras
       $booking_extras = cleanInput($_POST['booking_extras']);
//room_review
       $room_review = cleanInput($_POST['room_review']);
	   
    
//save the booking data if the error flag is still clear and booking id is > 0
    if ($error == 0 and $id > 0)
	  {
        $query = "UPDATE booking SET roomID=? , checkin_date=?,checkout_date=?,contact_number=?,booking_extras=?,room_review=? WHERE bookingID=?";
        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param($stmt,'isssssi', $roomID, $checkin_date, $checkout_date, $contact_number, $booking_extras, $room_review, $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details updated.</h2>";  
    } 
	  else
	  { 
      echo "<h2>$msg</h2>";
    }      
}
//locate the booking to edit by using the bookingID
//we also include the booking ID in our form for sending it back for saving the data
$query = 
	'SELECT * 
	FROM room
	INNER JOIN booking 
	ON room.roomID = booking.roomID
	INNER JOIN customer 
	ON booking.customerID = customer.customerID
	WHERE bookingID='.$id;
		
$result = mysqli_query($db_connection,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
 
   
// query for room dropdown
$query1 = 
	'SELECT * 
	FROM room';
	$rooms = mysqli_query($db_connection, $query1);
	$roomcount = mysqli_num_rows($rooms);
?>
 
<h1>Edit a booking</h1>
<h2><a href='currentbookings.php'>[Return to the Bookings listing]</a><a href="index.php">[Return to the main page]</a></h2>
<h2>Booking made for for Test</h2>
 
<form method="POST" action="editabooking.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  <p>
    <label for="roomID">Room (name,type,beds): </label>
	<select name="roomID" id="roomID">
	<?php
	if($roomcount > 0) {
		while ($room = mysqli_fetch_assoc($rooms)) {
			echo "<option value='".$room['roomID']."'>".$room['roomname'].",".$room['roomtype'].",".$room['beds']."</option>";
		}
	}
	else {
			echo "<option value = '0'> No room exists</option>";
	}
	?>
	</select>
  </p> 
  <p>
    <label for= "checkin_date">Checkin date:</label>
    <input type= "date" id= "checkin_date" name="checkin_date" value="<?php echo $row['checkin_date'] ?>" required>
  </p>  
  <p>  
    <label for="checkout_date">Checkout date:</label>
 

    <input type="date" id="checkout_date" name="checkout_date"  value="<?php echo $row['checkout_date'] ?>" required>
   </p>
  <p>
    <label for="contact_number">Contact number:</label>
 
    <input type="text" id= "contact_number" name="contact_number" value="<?php echo $row['contact_number'] ?>" required>
  </p>
  <p>
    <label for="booking_extras">Booking extras:</label>
 
    <textarea id="booking_extras" name="booking_extras" rows ="4" cols="50">
		<?php echo $row['booking_extras'] ?>
	</textarea>
  </p> 
  <p>
    <label for="room_review">Room review:</label>
 
    <textarea id="room_review" name="room_review" rows ="4" cols="50">
		<?php echo $row['room_review'] ?>
	</textarea>
  </p>   
   <input type= "submit" name= "submit" value= "Update"><a href='currentbookings.php'>[Cancel]</a>
   <?php
	echo '<a href="bookingpreviewbeforedeletion.php?id='.$id.'">[Delete]</a>';
   ?>
 </form>
<?php 
} 
else
{ 
  echo "<h2>room not found with that ID</h2>"; //simple error feedback
}
mysqli_close($db_connection); //close the connection once done
?> 
 
 
 
 <br>
 <br>
 <br>
 <a href='privacy.html'>[Privacy]</a>


</body>
</html>