<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    // Get the Data we need for this page
    $ttabs = xmlLoadAll("data/xml/tabs-operatingSystem.xml", "PLTab", "Tab");

    // Build UI Components with External HTML Loading
    foreach ($ttabs as $ttab) {
        $ttab->content = file_get_contents("data/html/{$ttab->content_href}");
    }
    $ttabhtml = renderUITabs($ttabs, "operatingSystem-content");

    // Construct the Page
    $tcontent = <<<PAGE
        <section class="row details" id="operatingSystem-overview">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Operating System Overview:</h3>
            </div>
            <div class="panel-body">
            {$ttabhtml}
            </div>
        </div>
    </section>
         
    PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Build up our Dynamic Content Items.
$tpagetitle = "Operating System Overview";
$tpagelead = "";
$tpagecontent = createPage();
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