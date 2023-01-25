<!DOCTYPE HTML>
<html lang="en">
<head>
  <title>Make a booking</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script 
		src="https://code.jquery.com/jquery-3.6.3.js"
		integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
		crossorigin="anonymous">
  </script>
  <script 
		src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"
		integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="
		crossorigin="anonymous">
  </script>
  <script>
  $(document.ready(function() {
    $(".datepicker").datepicker();
	
	$('#btn_search').click(function() {
		var checkin_date2 = $('#checkin_date2').val();
		var checkout_date2 = $('#checkout_date2').val();
		if (checkin_date2 != '' && checkout_date2 != '') {
			$.ajax({
				url: "roomsearch.php",
				method: "POST",
				data: { checkin_date2: checkin_date2, checkout_date2: checkout_date2 },
				success: function(data) {
					$('#tblroomavailability').html(data);
				}
			});
		}
		else {
			alert("Please select dates");
		}
	});
  });
  </script>
</head>
<body>
<?php
include "cleaninput.php";

//the data was sent using a formtherefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
    include "config.php"; //load in any variables
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data - only the first field is done for you in this example - rest is up to you do
$error = 0;
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

// query for date availability
$query2 =
	'SELECT * 
	FROM room';
	$result2 = mysqli_query($db_connection, $query2);


// query for room dropdown
$query1 = 
	'SELECT * 
	FROM room';
	$rooms = mysqli_query($db_connection, $query1);
	$roomcount = mysqli_num_rows($rooms);

       
//save the booking data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO booking (roomID,checkin_date,checkout_date,contact_number,booking_extras) VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($db_connection, $query); //prepare the query
        mysqli_stmt_bind_param($stmt,'issss', $roomID, $checkin_date, $checkout_date, $contact_number, $booking_extras); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>New booking added to the list</h2>";        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
    mysqli_close($db_connection); //close the connection once done


}
?> 
<h1>Make a booking</h1>
<h2><a href='currentbookings.php'>[Return to the Bookings listing]</a><a href="index.php">[Return to the main page]</a></h2>
<h2>Booking for Test</h2>


 
<form method="POST" action="makeabooking.php">
  <input type="hidden" name="id" value="">
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
			echo "<option value = '1'> No room exists</option>"; //should be '0' but used '1' to see if anything could get added to the db
	}
	?>
	</select>
  </p>
  <p>
    <label for= "checkin_date">Checkin date:</label>
    <input type= "date" id= "checkin_date" name="checkin_date" required value="">
  </p>  
  <p>  
    <label for="checkout_date">Checkout date:</label>
    <input type="date" id="checkout_date" name="checkout_date" required value="">
   </p>
  <p>
    <label for="contact_number">Contact number:</label>
    <input type="text" id= "contact_number" name="contact_number" value="" required>
  </p>
  <p>
    <label for="booking_extras">Booking extras:</label>
    <textarea id="booking_extras" name="booking_extras" rows ="4" cols="50">
	</textarea>
  </p>
     <input type= "submit" name= "submit" value= "Add"><a href='index.php'>[Cancel]</a>
 
</form>
<br>
<br>
<br>
<hr> 
<h2>Search for room availability</h2>

<form method="POST">
   <p>
    <label for= "checkin_date2">Checkin date:</label>
    <input type= "text" class="datepicker" id= "checkin_date2" name="checkin_date2" placeholder="Checkin Date">
		   
	<label for="checkout_date2">Checkout date:</label>
    <input type= "text" class="datepicker" id="checkout_date2" name="checkout_date2" placeholder="Checkout Date">
		   
	<input type= "button" name= "search" id="btn_search" value= "Search availability">
  </p>  
 </form>
 <table id="tblroomavailability" border="1">
 <thead><tr><th>Room #</th><th>Roomname</th><th>Room type</th><th>Beds</th></tr></thead>
 <?php
	
	include "config.php"; //load in any variables
    $db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
	
	$query2 =
	'SELECT * 
	FROM room';
	$result2 = mysqli_query($db_connection, $query2);
 
	while($row = mysqli_fetch_array($result2))
	{
		?>
			<tr>
				<td><?php echo $row["roomID"]; ?></td>
				<td><?php echo $row["roomname"]; ?></td>
				<td><?php echo $row["roomtype"]; ?></td>
				<td><?php echo $row["beds"]; ?></td>
			</tr>
		<?php
	}
	?>
 </table>
 <br>
 <br>
 <br>
 <a href='privacy.html'>[Privacy]</a>

</body>
</html>