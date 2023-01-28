<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");
?>
<!-- Create a cenrally located container -->
<div style="width:60%; margin: auto;">
<!-- Create a HTML Form within the container -->
<form action="checkForgetPw.php" method="post">
<!-- 1st row - Header Row -->
<div class="form-group row">
<div class="col-sm-9">
<span class="page-title">Please Enter Your Email Address</span>
</div>
</div>
<!-- 2nd row - Entry of email address -->
<div class="form-group row">
<label class="col-sm-3 col-form-label" for="email">
Email Address:
</label>
<div class="col-sm-9">
<input class="form-control" type="email"
name="email" id="email" required />
</div>
</div>
<!-- 4th row - Login button -->
<div class='form-group row'>
<div class='col-sm-9 offset-sm-3'>
<button class="btn btn-primary btn-sm" type='submit'>Submit</button>
</br></br>
</div>
</div>
</form>
</div>
<?php
// Include the Page Layout footer
include("footer.php");
?>