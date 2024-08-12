<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createReviewPage()
{
    $tmethod = appFormMethod();
    $taction = appFormActionSelf();

    $tcontent = <<<PAGE
        <form class="form-horizontal" method="{$tmethod}" action="{$taction}">
    	<fieldset>
    		<!-- Form Name -->
    		<legend>Write a review</legend>
            
    		<!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="devicename">Device Name</label>
    			<div class="col-md-4">
    				<input id="devicename" name="devicename" type="text" placeholder=""
    					class="form-control input-md" required=""> <span class="help-block">Enter
    					the name of the device as displayed on the device page.</span>
    			</div>
    		</div>
    
    		<!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="fname">First Name</label>
    			<div class="col-md-4">
    				<input id="fname" name="fname" type="text" placeholder=""
    					class="form-control input-md" required=""> <span class="help-block">Enter
    					your first name.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="lname">Last Name</label>
    			<div class="col-md-4">
    				<input id="lname" name="lname" type="text" placeholder=""
    					class="form-control input-md" required="" > 
                    <span class="help-block">Enter your last name.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="rating">Rating</label>
    			<div class="col-md-4">
    				<input id="rating" name="rating" type="number" placeholder=""
    					class="form-control input-md" required="" > 
                    <span class="help-block">Enter your rating (0 - 10).</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="title">Title</label>
    			<div class="col-md-4">
    				<input id="title" name="title" type="text" placeholder=""
    					class="form-control input-md" required="" > <span class="help-block">Enter
    					a title for the review.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="description">Description</label>
    			<div class="col-md-4">
    				<input id="description" name="description" type="text" placeholder=""
    					class="form-control input-md" required="" > 
                    <span class="help-block">Write your review here.</span>
    			</div>
    		</div>
       
    <!-- Button -->
    <div class="form-group">
    <label class="col-md-4 control-label" for="form-sub">Click to</label>
    <div class="col-md-4">
    <button id="form-sub" name="form-sub" type="submit" class="btn btn-info">Submit Review</button>
    </div>
    </div>
        </fieldset>
        </form>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------

session_start();
$tpagecontent = "";

$tdevid = "";
$tdevname = appFormProcessData($_REQUEST["devicename"] ?? "");
$tdevices = jsonLoadAllDevice();
// For loop links device name user enters to its id so correct reviews show up on correct pages
foreach ($tdevices as $tdevice) {
    if ($tdevice->devicename == $tdevname) {
        $tdevid = $tdevice->id;
    }
}

if (appFormMethodIsPost()) {
    // Map the Form Data
    $treview = new BLLUserReviews();
    $treview->deviceid = $tdevid;
    $treview->firstname = appFormProcessData($_REQUEST["fname"] ?? "");
    $treview->lastname = appFormProcessData($_REQUEST["lname"] ?? "");
    $treview->devicerating = appFormProcessData($_REQUEST["rating"] ?? "");
    $treview->reviewtitle = appFormProcessData($_REQUEST["title"] ?? "");
    $treview->reviewdesc = appFormProcessData($_REQUEST["description"] ?? "");
    $tscore = new BLLUserScores();
    $tscore->deviceid = $treview->deviceid;
    $tscore->devicerating = $treview->devicerating;

    $tvalid = true;
    // TODO: PUT SERVER-SIDE VALIDATION HERE

    if ($tvalid) {
        $tid = jsonNextUserReviewID();
        // Second variables used to add score to json for average score in rankings but didn't end up implementing this
        $tidsc = jsonNextUserScoreID();
        $treview->id = $tid;
        $tscore->id = $tidsc;
        // Convert the review to JSON and Score
        $tsavedata = json_encode($treview) . PHP_EOL;
        $tsavedata1 = json_encode($tscore) . PHP_EOL;
        // Get the existing contents and append the data
        $tfilecontent = file_get_contents("data/json/userReviews.json");
        $tfilecontent .= $tsavedata;
        $tfilecontent1 = file_get_contents("data/json/userscores.json");
        $tfilecontent1 .= $tsavedata1;
        // Save the file
        file_put_contents("data/json/userReviews.json", $tfilecontent);
        file_put_contents("data/json/userscores.json", $tfilecontent1);
        $tpagecontent = <<<SUCCESS
                <h1>Your review has been submited.</h1>
                <a class="btn btn-info" href="index.php">Home Page</a>
        SUCCESS;
    } else {
        $tdest = appFormActionSelf();
        $tpagecontent = <<<ERROR
                                 <div class="well">
                                    <h1>Form was Invalid</h1>
                                    <a class="btn btn-info" href="{$tdest}">Try Again</a>
                                 </div>
        ERROR;
    }
} else {
    // This page will be created by default.
    $tpagecontent = createReviewPage();
}
$tpagetitle = "Write a review";
$tpagelead = "";
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();

?>