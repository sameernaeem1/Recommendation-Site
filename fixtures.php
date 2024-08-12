<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createFixturePage($pfixtureid)
{
    //Get the Data we need for this page
    $tfixture = jsonLoadOneFixture($pfixtureid);    
    $tfixture->report = file_get_contents("data/html/{$tfixture->report_href}");
    //Build the UI Components
    $tfixturehtml = renderFixtureDetails($tfixture,"Latest Fixture");
    
    //Construct the Page
$tcontent = <<<PAGE
    <section class="row details" id="fixture-details">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Fixture Details</h3>
        </div>
        <div class="panel-body">
            {$tfixturehtml}
        </div>
    </div>
PAGE;
return $tcontent;
}

function createRecentFixturesPage()
{
    //Get the Data we need for this page
    $tfixturearray = jsonLoadAllFixture();
    $tfixtureshtml = "";
    $tfixtureshtml .= renderFixtureSummary($tfixturearray);

    //Construct the Page
    $tcontent = <<<PAGE
    <article id="fixtures">
        {$tfixtureshtml}
    </article>
PAGE;
    return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["fixid"] ?? -1;

//Handle our Requests and Search for Players
if (is_numeric($tid) && $tid > 0)
{
    $tpagecontent = createFixturePage($tid);
}
else 
{
    $tpagecontent = createRecentFixturesPage();
}

//Build up our Dynamic Content Items. 
$tpagetitle = "Fixture Information";
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