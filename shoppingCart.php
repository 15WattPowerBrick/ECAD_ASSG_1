<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price*Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
		echo "<div class='row justify-content-center'>";
		echo "<div class='border shadow-lg p-3 mb-5 bg-white rounded d-flex flex-column'>";
		echo "<p class='display-4' style='padding-left: 10px; padding-top: 10px;'>Shopping Cart</p>"; 
		echo "<div class='d-flex flex-row>";
		echo "<div class='col-auto>";
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class ='cart-header'>";
		echo "<tr class='bg-white text-dark'>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp</th>";
		echo "</tr>";
		echo "</thead>";
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
		$today = date("Y-m-d");
		// To Do 3 (Practical 4): 
		// Display the shopping cart content
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]";
			echo "</td>";
			//echo "Product ID: $row[ProductID]</td>";

			$qry2 = "SELECT * FROM Product WHERE ProductID=?";
			$stmt2 = $conn->prepare($qry2);
			$stmt2->bind_param("i", $row["ProductID"]);
			$stmt2->execute();
			$result2 = $stmt2->get_result();
			$stmt2->close();
			$row2 = $result2->fetch_array();

			if ($row2["Offered"] == 1 && $today >= $row2["OfferStartDate"] && $today <= $row2["OfferEndDate"]) {
				$isOffered = 1;
				$formattedPrice = number_format($row2["OfferedPrice"], 2);	
			}
			else{
				$formattedPrice = number_format($row["Price"], 2);
			}
			echo "<td>$formattedPrice</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { // To populate drop-down list from 1 to 10
				if ($i == $row["Quantity"])
					// Select drop-down list item with value same as the quantity of purchase
					$selected = "selected";
				else
					$selected = ""; // No specific item is selected
					echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			//$formattedTotal = number_format($row["Total"], 2);
			$total = $row["Quantity"] * $formattedPrice;
			$formattedTotal = number_format($total, 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // Column for remove item from shopping cart
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='images/trash-can.png' title='Remove Item' />";
			echo "</form>";
			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
										"name"=>$row["Name"],
										"price"=>$row["Price"],
										"quantity"=>$row["Quantity"]);
			// Accumulate the running sub-total
			$subTotal += $total;
			
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>";
		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart
		
			echo "</div>";
			echo "<div class='row bg-info text-light' style='padding-left: 50px;'>";
		echo "<h3 class='text-align-top text-align-left' style='padding-top:20px;'>Payment Information</h3>";
		//echo "<div class='col-auto w-75'>";

		echo "<p style='text-align:left; width: 100%; font-size:20px'>
				Subtotal: S$". number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);

		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page

			if (! isset($_SESSION["shippingType"])) {
				$_SESSION["shippingType"] = "normalDelivery";
			}

			if ($_SESSION["shippingType"] == "normalDelivery"){
				if ($subTotal > 200) {
					echo "<p><b>Selected: Normal Delivery (+$0)<br>(within 2 working days after an order is placed)</b><br><br></p>";
					echo "<div style='width: 100%;'><form method='post' action='setExpressDelivery.php'><button type='submit' class='btn btn-primary' style='font-size: 14px'>Switch to Express Delivery (+$0)</button></form></div>";
				}
				else{
					echo "<p><b>Selected: Normal Delivery (+$5)<br>(within 2 working days after an order is placed)</b><br><br></p>";
					echo "<div style='width: 100%;'><form method='post' action='setExpressDelivery.php'><button type='submit' class='btn btn-primary' style='font-size: 14px'>Switch to Express Delivery (+$10)</button></form></div>";
				}
			}
			else if($_SESSION["shippingType"] == "expressDelivery"){
				if ($subTotal > 200) {
					echo "<p><b>Selected: Express Delivery (+$0)<br>(delivered within 24 hours after an order is placed)</b><br><br></p>";
					echo "<div style='width: 100%;'><form method='post' action='setNormalDelivery.php'><button type='submit' class='btn btn-primary' style='font-size: 14px'>Switch to Normal Delivery (+$0)</button></form></div>";
				}
				else{
					echo "<p><b>Selected: Express Delivery (+$10)<br>(delivered within 24 hours after an order is placed)</b><br><br></p>";
					echo "<div style='width: 100%;'><form method='post' action='setNormalDelivery.php'><button type='submit' class='btn btn-primary' style='font-size: 14px'>Switch to Normal Delivery (+$5)</button></form></div>";
				}
			}

			echo "<form method='post' action='checkoutProcess.php'>";

			if (isset($_SESSION["ErrorMessage"])) {
				echo "<p style='font-weight: 600; margin-top: 10px; width: 100%'> Error: ". $_SESSION["ErrorMessage"];
				echo "</p>";
			}

			echo "<input type='image' style='float:left; padding:30px;'
					src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
			echo "</form></p>";
			echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>"; // End of Bootstrap responsive table
		
		
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer

unset($_SESSION["ErrorMessage"]);
?>
