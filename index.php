<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<!-- Landing Page -->
<div class="container px-4 px-lg-5">
    <!-- Heading Row-->
    <div class="row gx-4 gx-lg-5 align-items-center my-5">
        <div class="col-lg-7"><img class="img-fluid rounded mb-4 mb-lg-0" src="Images/happybabylanding.png" alt="...">
        </div>
        <div class="col-lg-5">
            <h1 class="font-weight-light">Welcome to HappyBaby</h1>
            <p>Welcome to HappyBaby, your one-stop-shop for all your baby's needs! From cribs to clothes, diapers to
                strollers, we have everything you need to care for your little joy. Our wide selection of high-quality
                products, competitive prices, and excellent customer service will make your shopping experience a
                pleasant one. We understand that being a parent is a big responsibility and that's why we are dedicated
                to providing you with the best products and services to make your life a little easier. Browse our
                website to find the perfect item for you and your baby.</p>
            <a class="btn btn-primary" href="search.php">Search for Products</a>
        </div>
    </div>
    <!-- Display Offer Products -->
    <?php
    include_once("mysql_conn.php");
    $qry = "SELECT * FROM product WHERE Offered = 1
    AND CURDATE() BETWEEN OfferStartDate AND OfferEndDate LIMIT 3;;
    ";
    $result = $conn->query($qry);

    if ($result->num_rows > 0) { // If found, display records
        echo ' 
        <div class="card text-white mt-5 py-4 text-center" style="background-color: #c0563d;">
        <div class="card-body">
            <h2 class="text-white m-0">Products on Offer!</h2>
        </div>
    </div>
        ';
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
            }
            echo '
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        echo ' </div>
            </div>
        </section>
        <div class="container pb-5">
  <div class="row">
    <div class="col-sm-12 text-center">
    <a class="btn btn-primary" href="search.php?keywords=&on_offer=on">View All Offers</a>
    </div>
  </div>
</div>

        
        
        
        
        
        
        ';
        
    } 
    $conn->close();
    ?>



    
</div>
<?php
// Include the Page Layout footer
include("footer.php");
?>