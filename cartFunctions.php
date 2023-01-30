<?php 
session_start();
if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'add':
        	addItem();
            break;
        case 'update':
            updateItem();
            break;
		case 'remove':
            removeItem();
            break;
    }
}

function addItem() {
	// Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 1
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	// Check if a shopping cart exist, if not create a new shopping cart
	if (! isset($_SESSION["Cart"])) {
		// Create a shopping cart for the shopper
		$qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["ShopperID"]);
		$stmt->execute();
		$stmt->close();
		$qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
		$result = $conn->query($qry);
		$row = $result->fetch_array();
		$_SESSION["Cart"] = $row["ShopCartID"];
	}

  	// If the ProductID exists in the shopping cart, 
  	// update the quantity, else add the item to the Shopping Cart.
  	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ii", $_SESSION["Cart"], $pid);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$addNewItem = 0;
	$addqty = 0;
	$today = date("Y-m-d");
	if ($result->num_rows > 0) {
		$qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10)
				WHERE ShopCartID=? AND ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
		$stmt->execute();
		$stmt->close();
		$addqty = $quantity;
	}
	else {
		$price = 0;
		$qry2 = "SELECT * FROM Product WHERE ProductID=?";
		$stmt2 = $conn->prepare($qry2);
		$stmt2->bind_param("i", $pid);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$stmt2->close();
		$row2 = $result2->fetch_array();
		if ($row2["Offered"] == 1 && $today >= $row2["OfferStartDate"] && $today <= $row2["OfferEndDate"]) {
			//$price = $row2["OfferedPrice"];
			$qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
					SELECT ?, ?, OfferedPrice, ProductTitle, ? FROM Product WHERE ProductID=?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
		}
		else{
			//$price = $row2["Price"];
			$qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
					SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
		}
		$stmt->execute();
		$stmt->close();
		$addNewItem = $quantity;
	}
  	$conn->close();
  	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])) {
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem + $addqty;
	}
	else {
		$_SESSION["NumCartItem"] = 1;
	}
	// Redirect shopper to shopping cart page
	header ("Location: shoppingCart.php");
	exit;
}

function updateItem() {
	// Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 2
	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	include_once("mysql_conn.php");

	$qry = "SELECT Quantity FROM ShopCartItem Where ProductID=? AND ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ii", $pid, $cartid);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$dbqty = (int) $row['Quantity'];
	if ($dbqty - $quantity > 0) {
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] - ($dbqty - $quantity);
	}
	else{
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + ($quantity - $dbqty);
	}
	$qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("iii", $quantity, $pid, $cartid);
	$stmt->execute();


	$stmt->close();
	$conn->close();
	header ("Location: shoppingCart.php");
	exit;
}

function removeItem() {
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 3
	// Write code to implement: if a user clicks on "Remove" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	if (isset($_POST['action'])) {
		include_once("mysql_conn.php");
		$cartid = $_SESSION["Cart"];
		$pid = $_POST["product_id"];
		$quantity = $_POST["quantity"];
		$qry = "SELECT Quantity FROM ShopCartItem Where ProductID=? AND ShopCartID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("ii", $pid, $cartid);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		$dbqty = (int) $row['Quantity'];
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] - $dbqty;
		$qry = "DELETE FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("ii", $pid, $cartid);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		header ("Location: shoppingCart.php");
		exit;
	}
}		
?>
