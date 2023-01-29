<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
include_once("mysql_conn.php");

$query = "SELECT * FROM Feedback";
$results=$conn->query($query);

echo '
    <div class="review"><a href="review.php">Review</a></div>
';
while ($row = $results->fetch_array()){
	echo '
    <div class="feedback-container">
        <div class="feedback-box">
            <div class="rank">
                '.$row["Rank"].'
            </div>
            <div class="content-body">
                <div class="subject">'.$row["Subject"].'</div>
                <div class="content">'.$row["Content"].'</div>
            </div>
        </div>
    </div>
    <div class="seperator"></div>
    ';
}


// Include the Page Layout footer
include("footer.php");
?>