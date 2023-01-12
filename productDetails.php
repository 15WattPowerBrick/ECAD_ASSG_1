<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

    <?php
    $pid = $_GET["pid"]; // Read Product ID from query string
    
    // Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");
    $qry = "SELECT * from product where ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); // "i" - integer 
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // To Do 1:  Display Product information. Starting ....
    while ($row = $result->fetch_array()) {
        // Display Page Header -
        // Product's name is read from the "ProductTitle" column of “product” table.
        $stock = $row["Quantity"];
        $today = date("Y-m-d");
        $isOffered = 0;
        $formattedOfferedPrice = "";
        if ($row["Offered"] == 1 && $today >= $row["OfferStartDate"] && $today <= $row["OfferEndDate"]) {
            $isOffered = 1;
            $formattedOfferedPrice = number_format($row["OfferedPrice"], 2);
        }
        $formattedPrice = number_format($row["Price"], 2);

        $qry = "SELECT s.SpecName, ps.SpecVal from productspec ps
                INNER JOIN specification s ON ps.SpecID=s.SpecID
                WHERE ps.ProductID=?
                ORDER BY ps.priority";

        $img = "./Images/products/$row[ProductImage]";
        


        echo '
    <section class="py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">';
        if ($isOffered == 1) {
            echo '<h3><div class="badge bg-danger text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div></h3>';
        }
        echo '
                <img class="card-img-top mb-5 mb-md-0" src="' . $img . '" alt="...">
            </div>
            <div class="col-md-6">
                <h1 class="display-5 fw-bolder">' . $row["ProductTitle"] . '</h1>
                <div class="fs-5 mb-5">';
        if ($isOffered == 1) {
            echo '<span><del>$' . $formattedPrice . '</del></span>
                    <span style="font-weight:bold; color:red;">$' . $formattedOfferedPrice . '</span>';
        } else {
            echo '<span style="font-weight:bold;">$' . $formattedPrice . '</span>';
        }
        echo '
                </div>
                <p class="lead">' . $row["ProductDesc"] . '</p>
                </br>';
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $pid); // "i" - integer
        $stmt->execute();
        $result2 = $stmt->get_result();
        $stmt->close();
        while ($row2 = $result2->fetch_array()) {
            echo '<p class="small">' . $row2["SpecName"] . ": " . $row2["SpecVal"] . '</p>';
        }

        echo "<form action='cartFunctions.php' method='post'>";
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";

        if ($stock <= 0) {
            echo "<button class='btn btn-outline-danger flex-shrink-0' type='submit' disabled>Out of Stock</button>";
        } else {
            echo "<div class='d-flex'>";
            echo "<input class='form-control text-center me-3' id='inputQuantity' type='number' name='quantity' value='1' min='1' max='10' style='max-width: 3rem' required />";
            echo "<button class='btn btn-outline-dark flex-shrink-0' type='submit'>";
            echo "Add to Cart</button>";
            echo "</div>";
        }
        echo "</form>";
        echo ' 
            </div>
        </div>
    </div>
</section>
    ';
    }



    // To Do 2:  Ending ....
    
    $conn->close(); // Close database connnection
    echo "</div>"; // End of container
    include("footer.php"); // Include the Page Layout footer
    ?>