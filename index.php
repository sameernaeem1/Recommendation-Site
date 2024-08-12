<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createDeviceSummaryPage()
{
    $tnilist = jsonLoadAllDevice();
    // Create the device items for each summary.
    $tdev = "";
    foreach ($tnilist as $tni) {
        $tdev .= renderDeviceAsSummary($tni);
    }
    $tcontent = <<<PAGE
    <div class="details">
    <h2>Devices We Have Reviewed</h2>
    {$tdev}
    </div>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Build up our Dynamic Content Items.
$tpagetitle = "Home";
$tpagelead = "";
$tpagecontent = createDeviceSummaryPage();
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