<?php 
//----INCLUDE APIS------------------------------------
include("api/api.inc.php");

//----PAGE GENERATION LOGIC---------------------------

function createNewsItemPage(BLLNewsItem $pitem)
{    
    $tnews = renderNewsItemFull($pitem);
    $tcontent = <<<PAGE
<div class="details">
{$tnews}
</div>
PAGE;
    return $tcontent;
}

function createNewsSummaryPage()
{
    $tnilist = jsonLoadAllNewsItems();    
    //Create the News Items for Article 2.
    $tnews = "";
    foreach($tnilist as $tni)
    {
       $tnews.=renderNewsItemAsSummary($tni);
    }
    $tcontent = <<<PAGE
<div class="details">
<h2>Latest News</h2>
{$tnews}
</div>
PAGE;
    return $tcontent;
}

//----BUSINESS LOGIC---------------------------------
//Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["storyid"] ?? -1;
//Handle our Requests and find a specific news story and display it
if (is_numeric($tid) && $tid > 0)
{
    $titem = jsonLoadOneNewsItem($tid);
    $tpagecontent = createNewsItemPage($titem);
}
//In this case, we want to display a summary of all news stories
else
{
    $tpagecontent = createNewsSummaryPage();
}

//Build up our Dynamic Content Items. 
$tpagetitle = "News Page";
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