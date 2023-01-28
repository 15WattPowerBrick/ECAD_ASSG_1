<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
include_once("mysql_conn.php");

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

$qry = "SELECT * FROM Shopper WHERE Email = ?";
$stmt = $conn->prepare($qry);

// To Do 1 (Practical 2): Validate login credentials with database
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0){
	$row = $result->fetch_array();
	$hashed_pwd = $row["Password"];
	If ($pwd == $hashed_pwd){
		$checkLogin = true;
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$_SESSION["ShopperEmail"] = $row["Email"];
		// To Do 2 (Practical 4): Get active shopping cart
		$qry = "SELECT sc.ShopCartID FROM ShopCart sc
				INNER JOIN ShopCartItem sci ON sc.ShopCartID=sci.ShopCartID
				WHERE sc.ShopperID=? AND sc.OrderPlaced=0";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["ShopperID"]);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0) {
			$row2 = $result2->fetch_array();
			$_SESSION["Cart"] = $row2["ShopCartID"];
			$_SESSION["NumCartItem"] = $result2->num_rows;
		}

		header("Location: index.php");
		exit();
	}
	else {
		echo '<script>
        alert("Password is wrong");
        window.location = "login.php";
        </script>';
	}
}
else{
	echo '<script>
        alert("Email does not exist!");
        window.location = "login.php";
        </script>';
}

/*
if (($email == "ecader@np.edu.sg") && ($pwd == "password")) {
	// Save user's info in session variables
	$_SESSION["ShopperName"] = "Ecader";
	$_SESSION["ShopperID"] = 1;
	
	// To Do 2 (Practical 4): Get active shopping cart
	
	// Redirect to home page
	header("Location: index.php");
	exit;
}
else {
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}
*/
	
// Include the Page Layout footer
include("footer.php");
?>