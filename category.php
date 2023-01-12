<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style="width:60%; margin:auto;">
    <!-- Display Page Header -->
    <div class="row" style="padding:5px"> <!-- Start of header row -->
        <div class="col-12">
            <span class="page-title">Product Categories</span>
            <p>Select a category listed below:</p>
        </div>
    </div> <!-- End of header row -->

    <?php
    // Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");

    // To Do:  Starting ....
    $qry = "SELECT * FROM Category ORDER BY CatName ASC;"; // Form SQL to select all categories
    $result = $conn->query($qry); // Execute the SQL and get the result
    echo '<div class="row gx-4 gx-lg-5">';
    // Display each category in a row
    while ($row = $result->fetch_array()) {
        $catname = urlencode($row["CatName"]);
        $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
        $img = "./Images/category/$row[CatImage]";

        echo '
    <div class="col-md-4 mb-5">
    <div class="card h-100">
    <!-- Product image-->
    <img class="card-img-top" src="' . $img . '" alt="...">
    <!-- Product details-->
    <div class="card-body p-4">
        <div class="text-center">
            <!-- Product name-->
            <h5 class="fw-bolder"><a href="' . $catproduct . '">' . $row["CatName"] . '</a></h5>
            <!-- Product price-->
            ' . $row["CatDesc"] . '
        </div>
    </div>
    <!-- Product actions-->
</div>
                </div>
    ';
    }
    echo '
</div>
';
    // To Do:  Ending ....
    
    $conn->close(); // Close database connnection
    echo "</div>"; // End of container
    include("footer.php"); // Include the Page Layout footer
    ?>