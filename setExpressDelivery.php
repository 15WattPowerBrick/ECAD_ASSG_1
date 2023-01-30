<?php   
session_start(); //to ensure you are using same session
$_SESSION["shippingType"] = "expressDelivery";
header("Location: shoppingCart.php"); //to redirect back to "index.php" after logging out
exit();
?>