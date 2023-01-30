<?php
session_start(); //Detect the current session

//Read the data input from previous page
$pwdanswer = $_POST["pwdanswer"];
$email = $_POST["email"];

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

$query = "SELECT * FROM Shopper WHERE Email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$row = $result->fetch_array();
if ($row["PwdAnswer"] == $pwdanswer)
{
    echo '<script>
    alert("Your password is: '.$row["Password"].'");
    window.location = "login.php";
    </script>';
}
else
{
    echo '<script>
        alert("Answer is wrong!");
        window.location = "forgetPassword.php";
        </script>';
}

$conn->close();


?>