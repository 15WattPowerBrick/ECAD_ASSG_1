<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
    <form name="frmSearch" method="get" action="">
        <div class="form-group row"> <!-- 1st row -->
            <div class="col-sm-9 ">
                <span class="page-title">Product Search</span>
            </div>
        </div> <!-- End of 1st row -->
        <div class="form-group row"> <!-- 2nd row -->
            <label for="keywords" class="col-sm-3 col-form-label">Product Title:</label>
            <div class="col-sm-6">
                <input class="form-control" name="keywords" id="keywords" type="search" />
            </div>
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div> <!-- End of 2nd row -->
    </form>

    <?php
    // The non-empty search keyword is sent to server
    if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
        // To Do (DIY): Retrieve list of product records with "ProductTitle" 
        // contains the keyword entered by shopper, and display them in a table.
        $SearchText = "%" . $_GET["keywords"] . "%";
        $keyword = $_GET["keywords"];
        // Include the PHP file that establishes database connection handle: $conn
        include_once("mysql_conn.php");
        $qry = "SELECT * FROM product WHERE 
    ProductTitle LIKE '%$SearchText%' OR ProductDesc LIKE '%$SearchText%' ORDER BY ProductTitle";
        $result = $conn->query($qry);

        if ($result->num_rows > 0) { // If found, display records
            echo '<header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Results for: "' . $keyword . '"</h1>
                </div>
            </div>
        </header>';
            echo ' <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">';
            while ($row = $result->fetch_array()) {
                $today = date("Y-m-d");
                $isOffered = 0;
                $formattedOfferedPrice = "";
                if ($row["Offered"] == 1 && $today >= $row["OfferStartDate"] && $today <= $row["OfferEndDate"]) {
                    $isOffered = 1;
                    $formattedOfferedPrice = number_format($row["OfferedPrice"], 2);
                }
                $formattedPrice = number_format($row["Price"], 2);
                $product = "productDetails.php?pid=$row[ProductID]";
                $img = "./Images/products/$row[ProductImage]";
                echo ' 
            <div class="col-md-4 mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <div >';
                if ($isOffered == 1) {
                    echo '<div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>';
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
                        </div>
                    </div>';
            }
            echo ' </div>
            </div>
        </section>';
        } else {
            echo "<b>No Records Found!</b><br/>";
        }
        $conn->close();

        // To Do (DIY): End of Code
    }

    echo "</div>"; // End of container
    include("footer.php"); // Include the Page Layout footer
    ?>