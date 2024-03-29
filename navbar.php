<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 

    $content1 = "Welcome ". $_SESSION["ShopperName"]; 

    $content2 = "<li class='nav-item'>
            <a class='nav-link' href='editProfile.php'>Edit Profile</a></li>
            <li class='nav-item'>
            <a class='nav-link' href='logout.php'>Logout</a></li>";

    //Display number of item in cart
    if (isset($_SESSION["NumCartItem"])) {
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
	
}
?>
<!-- Display a navbar which is visible before or after collapsing -->
     <nav class="navbar navbar-expand-md navbar-dark" style="background-color: #c0563d;">
<!-- Dynamic Text Display-->
<span class="navbar-text ml-md-2"
    style="color: #F7BE81; max-width: 80%;">
<?php echo $content1; ?>
</span>
<!-- Toggler/Collapsibe Button -->
<button class="navbar-toggler" type="button" data-toggle="collapse"
    data-target="#collapsibleNavbar">
<span class="navbar-toggler-icon"></span>
</button>
</nav>

<!-- Define a collapsible navbar -->
     <nav class="navbar navbar-expand-md navbar-dark "style="background-color: #c0563d;">

<!-- Collapsible part of navbar -->
<div class="collapse navbar-collapse" id="collapsibleNavbar">
<!-- Left-justified menu items -->

<ul class="navbar-nav mr-auto">
<li class="nav-item" >
<a class="nav-link" href="category.php">Product Categories</a>
</li>|
<li class="nav-item" >
<a class="nav-link" href="search.php">Product Search</a>
</li>
<li class="nav-item" >
<a class="nav-link" href="shoppingCart.php">Shopping Cart</a>
</li>
<li class="nav-item" >
<a class="nav-link" href="feedback.php">Feedback</a>
</li>
</ul>
<!-- Right-justified menu items -->
<ul class="navbar-nav ml-auto">
<?php echo $content2; ?>
</ul>
</div>
</nav>


