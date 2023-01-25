<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<title>Customer Login</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
<body>

<?php
	include "config.php";
	include "cleaninput.php";
	$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
	if (mysqli_connect_errno()) {
		echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
		exit; //stop processing further
	};
// if the login form has been filled in
	if (isset($_POST['email']))
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
//prepare a query and send it to the server
		$stmt = mysqli_stmt_init($db_connection);
		mysqli_stmt_prepare($stmt, "SELECT customerID, password, role FROM customer WHERE email=?");
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $customerID, $hashpassword, $role);
		mysqli_stmt_fetch($stmt);
		
		if(!$customerID)
		{
			echo '<p class="error">Unable to find customer with email!'.$email.'</p>';
		}
		else
		{	
			if (password_verify($password, $hashpassword))
			{
				$_SESSION['loggedin'] = true;
				$_SESSION['email'] = $email;
				$_SESSION['role'] = $role;
				echo '<p>Congratulations, you are logged in!</p>';
				echo $role;
			}
			else
			{
				echo '<p>Username/password combination is wrong!</p>';
			}
		}
		echo '<p><a href="index.php">Return to the menu</a></p>';
		mysqli_stmt_close($stmt);
	}
mysqli_close($db_connection); //close the connection once done
?>
<!-- the action is to this page so the form will also submit to this page -->
<form method="POST" action="login.php">
	<h1>Customer Login</h1>
	<p>
	<label for="email">Email address: </label>
	<input type="email" id="email" size="50" name="email" required>
	</p>
	<p>
	<label for="password">Password: </label>
	<input type="password" id="password" size="50" min="1" max="30" required>
	</p>
	<input type="submit" name="submit" value="Login">
	<a href='login.php'>[Logout]</a>
</form>
</body>
</html>