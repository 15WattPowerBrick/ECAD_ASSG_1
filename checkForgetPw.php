<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 
include_once("mysql_conn.php");

// Reading inputs entered in previous page
$email = $_POST["email"];

$qry = "SELECT * FROM Shopper WHERE Email = ?";
$stmt = $conn->prepare($qry);

$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0){
	$row = $result->fetch_array();
	if($row["PwdQuestion"] != "" || $row["PwdAnswer"] != ""){
        echo '
        <div style="width:80%; margin:auto;">
<form name="register" action="checkPwd.php" method="post">
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Enter your answer for Question</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdquestion">Question:</label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdquestion" id="pwdquestion" value="'.$row["PwdQuestion"].'" type="text" readonly="readonly"/>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdanswer">Answer to question:</label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdanswer" id="pwdanswer" type="text" />
        </div>
        <input class="form-control" name="email" id="email" 
                   type="hidden" value="'.$email.'"  />
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary btn-sm" type="submit">Submit</button>
        </div>
    </div>
</form>
</div>
        ';
    }
    else{
        echo '<script>
        alert("Account does not have Question and Answer to retrieve account");
        window.location = "forgetPassword.php";
        </script>';
    }
}
else{
	echo '<script>
        alert("Email does not exist!");
        window.location = "forgetPassword.php";
        </script>';
}

/*
if (($email == "ecader@np.edu.sg") && ($pwd == "password")) {
	// Save user's info in session variables
	$_SESSION["ShopperName"] = "Ecader";
	$_SESSION["ShopperID"] = 1;
	
	// To Do 2 (Practical 4): Get active shopping cart
	
	// Redirect to home page
	header("Location: index.php");
	exit;
}
else {
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}
*/
	
// Include the Page Layout footer
include("footer.php");
?>