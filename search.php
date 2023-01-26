<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:60%; margin:auto;"> <!-- Container -->
    <form name="frmSearch" method="get" action="">
        <div class="form-group row"> <!-- 1st row -->
            <div class="col-sm-9 ">
                <span class="page-title">Product Search</span>
            </div>
        </div> <!-- End of 1st row -->
        <div class="form-group row"> <!-- 2nd row -->
            <div class="col-sm-9">
                <input class="form-control" name="keywords" id="keywords" type="search" placeholder="Product"
                value="<?php echo !empty($_GET["keywords"]) ? $_GET["keywords"] : ""; ?>" />
            </div> 
            <!-- <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div> -->
        </div> <!-- End of 2nd row -->

        <div class="form-group row"> <!-- 3rd row -->
    <div class="col-sm-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="on_offer" id="on_offer" value="on_offer" 
            <?php echo !empty($_GET["on_offer"]) ? "checked" : ""; ?>>
            <label class="form-check-label" for="on_offer">On Offer</label>
        </div>
    </div>
</div> <!-- End of 3rd row -->



        <div class="form-group row"> <!-- 4th row -->
            <div class="col-sm-4">
                <input class="form-control" name="min_price" id="min_price" type="number" min='0' placeholder="Min. Price"
                value="<?php echo !empty($_GET["min_price"]) ? $_GET["min_price"] : ""; ?>"/>
            </div>
            <div class="col-sm-4">
                <input class="form-control" name="max_price" id="max_price" type="number" min='0' placeholder="Max. Price" 
                value="<?php echo !empty($_GET["max_price"]) ? $_GET["max_price"] : ""; ?>"/>
            </div>
        </div> <!-- End of 4th row -->

        <div class="form-group row"> <!-- 5th row -->
            
            <div class="col-sm-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div> <!-- End of 5th row -->
    </form>

    <?php


    // The non-empty search keyword is sent to server
    if ((isset($_GET["keywords"]) && trim($_GET['keywords']) != "") || !empty($_GET["on_offer"]) ||  !empty($_GET["min_price"]) || !empty($_GET["max_price"])) {

        $qry = "SELECT * FROM product WHERE";

            $today = date("Y-m-d");
            $criteria = 0;


            if (!empty($_GET["keywords"])) {
                $keywords = $_GET["keywords"];
                $qry .= "  (ProductTitle LIKE '%$keywords%' OR ProductDesc LIKE '%$keywords%')";
                $criteria = 1;
            }
            if (!empty($_GET["on_offer"])) {
                $today = date("Y-m-d");
                if ($criteria == 1) {
                    $qry .= " AND ";
                }
                $qry .= "  Offered = 1 AND '$today' >= OfferStartDate AND '$today' <= OfferEndDate";
                $criteria = 1;
            }
            
            if (!empty($_GET["min_price"])) {
                //$qry .= " AND Price >= ".$_GET["min_price"]."";
                if ($criteria == 1) {
                    $qry .= " AND ";
                }
                $qry .= "  (CASE WHEN Offered = 1 AND '$today' >= OfferStartDate AND '$today' <= OfferEndDate THEN OfferedPrice ELSE Price END) >= " . $_GET["min_price"] . "";
                $criteria = 1;
            }
            if (!empty($_GET["max_price"])) {
                //$qry .= " AND Price <= ".$_GET["max_price"]."";
                if ($criteria == 1) {
                    $qry .= " AND ";
                }
                $qry .= "  (CASE WHEN Offered = 1 AND '$today' >= OfferStartDate AND '$today' <= OfferEndDate THEN OfferedPrice ELSE Price END) <= " . $_GET["max_price"] . "";
                $criteria = 1;
            }
            $qry .= " ORDER BY ProductTitle";
        
        

        // echo $qry;

        // To Do (DIY): Retrieve list of product records with "ProductTitle" 
        // contains the keyword entered by shopper, and display them in a table.
        $SearchText = "%" . $_GET["keywords"] . "%";
        $keyword = $_GET["keywords"];
        // Include the PHP file that establishes database connection handle: $conn
        include_once("mysql_conn.php");
        //     $qry = "SELECT * FROM product WHERE 
        // ProductTitle LIKE '%$SearchText%' OR ProductDesc LIKE '%$SearchText%' ORDER BY ProductTitle";
        $result = $conn->query($qry);

        if ($result->num_rows > 0) { // If found, display records
            echo '
            <div class="card text-white mt-5 py-4 text-center" style="background-color: #c0563d;">
        <div class="card-body">
            <h2 class="text-white m-0">Results</h2>
            <!-- <h2 class="text-white m-0">Results for: "' . $keyword . '"</h2> -->
        </div>
    </div>';
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
                        </div>
                    </div>';
            }
            echo ' </div>
            </div>
        </section>';
        } else {
            echo '
            <div class="card text-white my-5 py-4 text-center" style="background-color: #c0563d;">
        <div class="card-body">
            <h2 class="text-white m-0">No Records Found!</h2>
            <!-- <h2 class="text-white m-0">No Records Found!</h2> -->
        </div>
    </div>';
        }
        $conn->close();

        // To Do (DIY): End of Code
    }

    echo "</div>"; // End of container
    include("footer.php"); // Include the Page Layout footer
    ?>