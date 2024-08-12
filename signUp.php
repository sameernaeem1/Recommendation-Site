<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createFormPage()
{
    $tmethod = appFormMethod();
    $taction = appFormActionSelf();
    $toptions = "";
    $tdevices = jsonLoadAllDevice();
    foreach ($tdevices as $tdevice) {
        $toptions .= <<<SELECT
                    <option value="{$tdevice->devicename}">{$tdevice->devicename}</option>
        SELECT;
    }
    $tcontent = <<<PAGE
        <form class="form-horizontal" method="{$tmethod}" action="{$taction}">
    	<fieldset>
    		<!-- Form Name -->
    		<legend>Create an account</legend>
            
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
    					class="form-control input-md" required=""> <span class="help-block">Enter
    					your last name.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="username">Username</label>
    			<div class="col-md-4">
    				<input id="username" name="username" type="text" placeholder=""
    					class="form-control input-md" required="" > 
                    <span class="help-block">Enter your username.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="password">Password</label>
    			<div class="col-md-4">
    				<input id="password" name="password" type="password" placeholder=""
    					class="form-control input-md" required="" > 
                    <span class="help-block">Enter your password.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="email">Email</label>
    			<div class="col-md-4">
    				<input id="email" name="email" type="email" placeholder=""
    					class="form-control input-md" > <span class="help-block">Enter
    					your email.</span>
    			</div>
    		</div>
            
            <!-- Text input-->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="phonenumber">Phone Number</label>
    			<div class="col-md-4">
    				<input id="phonenumber" name="phonenumber" type="number" placeholder=""
    					class="form-control input-md" > 
                    <span class="help-block">Enter your phone number.</span>
    			</div>
    		</div>
            
    		<!-- Select Basic -->
    		<div class="form-group">
    			<label class="col-md-4 control-label" for="favedevice">Favourite Device</label>
    			<div class="col-md-4">
    				<select id="favedevice" name="favedevice" class="form-control">
                        <option value=""></option>
                        {$toptions}
    				</select>
                    <span class="help-block">Select your favourite device.</span>
    			</div>
    		</div>
    
    <!-- Button -->
    <div class="form-group">
    <label class="col-md-4 control-label" for="form-sub">Click to</label>
    <div class="col-md-4">
    <button id="form-sub" name="form-sub" type="submit" class="btn btn-info">Create Account</button>
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

if (appFormMethodIsPost()) {
    $tuser = new BLLUser();
    $tuser->firstname = appFormProcessData($_REQUEST["fname"] ?? "");
    $tuser->lastname = appFormProcessData($_REQUEST["lname"] ?? "");
    $tuser->username = appFormProcessData($_REQUEST["username"] ?? "");
    $tuser->password = appFormProcessData($_REQUEST["password"] ?? "");
    $tuser->email = appFormProcessData($_REQUEST["email"] ?? "");
    $tuser->phonenumber = appFormProcessData($_REQUEST["phonenumber"] ?? "");
    $tuser->favedevice = appFormProcessData($_REQUEST["favedevice"] ?? "");
    $tvalid = true;

    if ($tvalid) {
        $tid = jsonNextUserID();
        $tuser->id = $tid;
        // Convert the User to JSON
        $tsavedata = json_encode($tuser) . PHP_EOL;
        // Get the existing contents and append the data
        $tfilecontent = file_get_contents("data/json/users.json");
        $tfilecontent .= $tsavedata;
        // Save the file
        file_put_contents("data/json/users.json", $tfilecontent);
        $tpagecontent = <<<SUCCESS
                <h1>Your account has been created.</h1>
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
    $tpagecontent = createFormPage();
}
$tpagetitle = "Sign up";
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