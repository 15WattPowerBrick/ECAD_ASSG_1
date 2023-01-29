<?php
session_start(); //Detect the current session

//Read the data input from previous page
$rank = $_POST["rank"];
$subject = $_POST["subject"];
$content = $_POST["content"];
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Define the INSERT SQL statement
$qry = "INSERT INTO Feedback (ShopperID, Subject, Content, Rank) VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($qry);

$stmt->bind_param("ssss", $_SESSION["ShopperID"], $subject, $content, $rank);

if ($stmt->execute()) {
    echo '<script>
    alert("Review successful added!");
    window.location = "feedback.php";
    </script>';
}
else {
    echo '<script>
    alert("Error inserting record");
    window.location = "feedback.php";
    </script>';
}


$stmt->close();

$conn->close();


?>