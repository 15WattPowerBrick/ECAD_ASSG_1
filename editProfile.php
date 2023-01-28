<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
include_once("mysql_conn.php");
?>

<script type="text/javascript">
function validateForm()
{
    // Check if password matched
	if (document.register.password.value != document.register.password2.value){
        alert("Passwords not matched!");
        return false;
    }

    return true;  // No error found
}
</script>
<?php
$qry = "SELECT * FROM Shopper WHERE ShopperID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $_SESSION["ShopperID"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $name = $row["Name"];
    $dob = $row["BirthDate"];
    $address = $row["Address"];
    $country = $row["Country"];
    $phone = $row["Phone"];
    $email = $row["Email"];
    $pwdquestion = $row["PwdQuestion"];
    $pwdanswer = $row["PwdAnswer"];
}
echo '
<div style="width:80%; margin:auto;">
<form name="register" action="checkEdit.php" method="post" 
      onsubmit="return validateForm()">
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Edit Profile</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="name">Name:</label>
        <div class="col-sm-9">
            <input class="form-control" name="name" id="name" 
                   type="text" value="'.$name.'" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="dob">Date of Birth:</label>
        <div class="col-sm-9">
            <input class="form-control" name="dob" id="dob" 
                   type="date" value="'.$dob.'" required/> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="address">Address:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4" >'.$address.'</textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="country">Country:</label>
        <div class="col-sm-9">
            <input class="form-control" name="country" id="country" value="'.$country.'" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-9">
            <input class="form-control" name="phone" id="phone" value="'.$phone.'" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-9">
            <input class="form-control" name="email" id="email" 
                   type="email" value="'.$email.'" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password">
            New Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password" id="password" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="password2">
            Retype Password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="password2" id="password2" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdquestion">Question to be challenged before revealing password in forget password:</label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdquestion" id="pwdquestion" value="'.$pwdquestion.'" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pwdanswer">Answer to question:</label>
        <div class="col-sm-9">
            <input class="form-control" name="pwdanswer" id="pwdanswer" value="'.$pwdanswer.'" type="text" />
        </div>
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary btn-sm" type="submit">Update</button>
        </div>
    </div>
</form>
</div>
'
?>
<?php
// Include the Page Layout footer
include("footer.php");
?>