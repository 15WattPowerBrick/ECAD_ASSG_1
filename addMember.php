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
$qry = "INSERT INTO Shopper (Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($qry);

$stmt->bind_param("sssssssss", $name, $dob, $address, $country, $phone, $email, $password, $pwdquestion, $pwdanswer);
$boolean = FALSE;
$query = "SELECT Email FROM Shopper";
$results=$conn->query($query);

while ($row = $results->fetch_array()){
	if ($email == $row["Email"]){
        $boolean = TRUE;
    }
}
if ($boolean == FALSE)
{
    if ($stmt->execute()) {
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry);
        while($row = $result->fetch_array()){
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
        $message = "Registration successful!
                    Your ShopperID is $_SESSION[ShopperID]";
        echo '<script>
        alert("Registration successful!");
        window.location.href="http://localhost/ECAD_ASSG_1/index.php";
        </script>';
        $_SESSION["ShopperName"] = $name;
    }
    else {
        echo '<script>
        alert("Error inserting record");
        window.location.href="http://localhost/ECAD_ASSG_1/register.php";
        </script>';
    }
}
else
{
    echo '<script>
        alert("Email exist in database!");
        window.location.href="http://localhost/ECAD_ASSG_1/register.php";
        </script>';
}
$stmt->close();

$conn->close();


?>