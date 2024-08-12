<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createManagerPage(BLLManager $pman)
{ 
    $tmanhtml = renderManagerOverview($pman);
$tcontent = <<<PAGE
    {$tmanhtml}
PAGE;
return $tcontent;
}

function createExecutivePage(BLLExecutive $pexec)
{
    $texechtml = renderExecutiveOverview($pexec);
    $tcontent = <<<PAGE
    {$texechtml}
PAGE;
    return $tcontent;
}

function createCoachPage(BLLCoaching $pcoach)
{
    $tcoachhtml = renderCoachOverview($pcoach);
    $tcontent = <<<PAGE
    {$tcoachhtml}
PAGE;
    return $tcontent;
}


//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["id"] ?? -1;
$tname = $_REQUEST["type"] ?? "";

//Boolean valid to check for page validity.
$tvalid = false;

if($tname === "exec")
{
    //Handle our Requests and Search for Players
    if (is_numeric($tid) && $tid > 0)
    {
        $texec = jsonLoadOneExecutive($tid);
        $tpagecontent = createExecutivePage($texec);
        $tvalid = true;
    }
}
else if($tname === "coach")
{
    //Handle our Requests and Search for Players
    if (is_numeric($tid) && $tid > 0)
    {
        $tcoach = jsonLoadOneCoaching($tid);
        $tpagecontent = createCoachPage($tcoach);
        $tvalid = true;
    }    
}
else if($tname === "manager")
{
    //Handle our Requests and Search for Players
    if (is_numeric($tid) && $tid > 0)
    {
        $tmanager = jsonLoadOneManager($tid);
        $tpagecontent = createManagerPage($tmanager);
        $tvalid = true;
    }    
}

//We do not have a valid page.
if(!$tvalid)
{
    header("Location: app_error.php");
    return;
}

//If we get to here, $tvalid must be true....

//Build up our Dynamic Content Items. 
$tpagetitle = "Staff Page";
$tpagelead  = "";
$tpagefooter = "";

//----BUILD OUR HTML PAGE----------------------------
//Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
//Set the Three Dynamic Areas (1 and 3 have defaults)
if(!empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if(!empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
//Return the Dynamic Page to the user.    
$tpage->renderPage();
?>