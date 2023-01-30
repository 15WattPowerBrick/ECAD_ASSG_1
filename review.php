<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php");

if ($_SESSION["ShopperID"] == NULL){
    echo '
        <script>
            alert("Please login or register to review");
            window.location = "feedback.php";
        </script>
    ';
}
?>

<div style="width:80%; margin:auto;">
<form name="review" action="checkReview.php" method="post">
    <div class="form-group row">
        <div class="col-sm-9">
            <span class="page-title">Review</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="rank">Rank (1-lowest, 2-highest): </label>
        <div class="col-sm-9">
            <input name="rank" value="1" 
                    type="radio" required />
            <label class="col-sm-1 col-form-label" for="1">1</label>
            <input name="rank" value="2"  
                    type="radio" required />
            <label class="col-sm-1 col-form-label" for="2">2</label>
            <input name="rank" value="3" 
                    type="radio" required />
            <label class="col-sm-1 col-form-label" for="3">3</label>
            <input name="rank" value="4" 
                    type="radio" required />
            <label class="col-sm-1 col-form-label" for="4">4</label>
            <input name="rank" value="5" 
                    type="radio" required />
            <label class="col-sm-1 col-form-label" for="5">5</label>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="subject">Subject:</label>
        <div class="col-sm-9">
            <input class="form-control" name="subject" id="subject" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="content">Content:</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="content" id="content"
                      cols="25" rows="4" ></textarea>
        </div>
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button class="btn btn-primary btn-sm" type="submit">Review</button>
        </div>
    </div>
</form>
</div>

<?php
// Include the Page Layout footer
include("footer.php");
?>