<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
	<!-- Display Page Header - Category's name is read 
	 from the query string passed from previous page -->
	<div class="row" style="padding:5px">
		<div class="col-12">
			<span class="page-title">
				<?php echo "$_GET[catName]"; ?>
				<p></p>
			</span>
		</div>
	</div>

	<?php
	// Include the PHP file that establishes database connection handle: $conn
	include_once("mysql_conn.php");
	
	$cid = $_GET["cid"]; // Read Category ID from query string
// Form SQL to retrieve list of products associated to the Category ID
	$qry = "SELECT p.*
FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
WHERE cp.CategoryID=? ORDER BY p.ProductTitle ASC;";

	$stmt = $conn->prepare($qry);

	$stmt->bind_param("i", $cid); // “i" - integer
	
	$stmt->execute();

	$result = $stmt->get_result();

	$stmt->close();
	echo '<div class="row gx-4 gx-lg-5">';
	// Display each product in a row
	while ($row = $result->fetch_array()) {
		$product = "productDetails.php?pid=$row[ProductID]";
		$today = date("Y-m-d");
		$isOffered = 0;
		$formattedOfferedPrice = "";
		if ($row["Offered"] == 1 && $today >= $row["OfferStartDate"] && $today <= $row["OfferEndDate"]) {
			$isOffered = 1;
			$formattedOfferedPrice = number_format($row["OfferedPrice"], 2);
		}
		$formattedPrice = number_format($row["Price"], 2);
		$img = "./Images/products/$row[ProductImage]";
		echo '
    <div class="col-md-4 mb-5">
    <div class="card h-100">
    <!-- Product image-->
    <div >';
		if ($isOffered == 1) {
			echo '<h3><div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div></h3>';
		}
		echo '
                            <img class="card-img-top" src="' . $img . '" alt="...">
                            </div>
    <!-- Product details-->
    <div class="card-body p-4">
        <div class="text-center">
            <!-- Product name-->
            <h5 class="fw-bolder"><a href="' . $product . '">' . $row["ProductTitle"] . '</a></h5>
            <!-- Product price-->';
			if ($isOffered == 1) {
				echo '<span><del>$' . $formattedPrice . '</del></span>
											<span style="font-weight:bold; color:red;">$' . $formattedOfferedPrice . '</span>';
			} else {
				echo '<span style="font-weight:bold; ">$' . $formattedPrice . '</span>';
			}
			echo '
        </div>
    </div>
    <!-- Product actions-->
</div>
                </div>
    ';



	}
	echo '</div>';
	// To Do:  Ending ....
	
	$conn->close(); // Close database connnection
	echo "</div>"; // End of container
	include("footer.php"); // Include the Page Layout footer
	?>