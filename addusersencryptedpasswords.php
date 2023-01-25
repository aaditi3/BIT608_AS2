<!DOCTYPE HTML>
<html>
	<body>
		<?php
		include "config.php";
		$db_connection = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
		if (mysqli_connect_errno()) {
				echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
				exit; //stop processing further
		};
		$query = "INSERT INTO customer (firstname, lastname, email, password, role) VALUES (?,?,?,?,?)";
		$stmt = mysqli_prepare($db_connection, $query); //prepare the query
// create hashed password - used for both members
		$password="temp1234";
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// admin user
		$firstname = "The";
		$lastname = "Admin";
		$email = "admin@customeradmin.co.nz";
		$role = 9;
		mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $hashed_password, $role);
		mysqli_stmt_execute($stmt);
// ordinary customer
		$firstname = "Ordinary";
		$lastname = "Customer";
		$email = "acustomer@acustomer.co.nz";
		$role = 1;
		mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $hashed_password, $role);
		mysqli_stmt_execute($stmt);
// non customer
		$firstname = "Non";
		$lastname = "Customer";
		$email = "noncustomer@noncustomer.co.nz";
		$role = 0;
		mysqli_stmt_bind_param($stmt, 'ssssi', $firstname, $lastname, $email, $hashed_password, $role);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		mysqli_close($db_connection); //close the connection once done
		?>
		<p>test data added to the database</p>
	</body>
</html>
