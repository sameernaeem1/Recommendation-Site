<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createDevicePage($pdeviceid)
{
    // Get the Data we need for this page
    $tdevice = jsonLoadOneDevice($pdeviceid);
    // Build the UI Components
    $tdevicehtml = renderDeviceOverview($tdevice);

    // Construct the Page
    $tcontent = <<<PAGE
        <section class="row details" id="device-details">
            <div class="well">
                {$tdevicehtml}
            </div>
        </div>
    PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["deviceid"] ?? - 1;

// Handle our Requests and Search for Players
if (is_numeric($tid) && $tid > 0) {
    $tpagecontent = createDevicePage($tid);
} else {}

// Build up our Dynamic Content Items.
$tdevicetitle = jsonLoadOneDevice($tid);
$tpagetitle = "{$tdevicetitle->devicename}";
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