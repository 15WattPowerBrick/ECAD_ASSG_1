<?php
session_start(); //Detect the current session

//Read the data input from previous page
$name = $_POST["name"];
$dob = $_POST["dob"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$pwdquestion = $_POST["pwdquestion"];
$pwdanswer = $_POST["pwdanswer"];


// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Define the INSERT SQL statement
$qry = "UPDATE Shopper SET Name = ?, Birthdate = ?, Address = ?, Country = ?, Phone = ?, Email = ?, Password = ?, PwdQuestion = ?, PwdAnswer = ? WHERE ShopperID = ?";

$stmt = $conn->prepare($qry);

$stmt->bind_param("sssssssssi", $name, $dob, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer, $_SESSION["ShopperID"]);
$boolean = FALSE;
$query = "SELECT Email FROM Shopper";
$results=$conn->query($query);

while ($row = $results->fetch_array()){
	if ($email == $row["Email"] && $email != $_SESSION["ShopperEmail"]){
        $boolean = TRUE;
    }
}
if ($boolean == FALSE)
{
    $stmt->execute();
    echo '<script>
    alert("Update Successfully!");
    window.location.href="http://localhost/ECAD_ASSG_1/index.php";
    </script>';
    $_SESSION["ShopperName"] = $name;
}
else
{
    echo '<script>
        alert("Email exist in database!");
        window.location.href="http://localhost/ECAD_ASSG_1/editProfile.php";
        </script>';
}
$stmt->close();

$conn->close();


?>