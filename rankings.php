<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pimgdir, $pcurrpage, $psortmode, $psortorder)
{
    // Get the Presentation Layer content
    $talldevices = new BLLallDevices();
    $talldevices->devicelist = jsonLoadAllDevice();

    // We need to sort the squad using our custom class-based sort function
    $tsortfunc = "";
    if ($psortmode == "device")
        $tsortfunc = "alldevicessortbyname";
    else if ($psortmode == "review")
        $tsortfunc = "alldevicessortbyreview";

    // Only sort the array if we have a valid function name
    if (! empty($tsortfunc))
        usort($talldevices->devicelist, $tsortfunc);

    // The pagination working out how many elements we need and
    $tnoitems = sizeof($talldevices->devicelist);
    $tperpage = 5;
    $tnopages = ceil($tnoitems / $tperpage);

    // Create a Pagniated Array based on the number of items and what page we want.
    $tfilteralldevices = appPaginateArray($talldevices->devicelist, $pcurrpage, $tperpage);
    $talldevices->devicelist = $tfilteralldevices;

    // Render the HTML for our Table and our Pagination Controls
    $talldevicetable = renderDeviceTable($talldevices);
    $tpagination = renderPagination($_SERVER['PHP_SELF'], $tnopages, $pcurrpage);

    // Use the Presentation Layer to build the UI Elements

    $tcontent = <<<PAGE
    		<div class="row">
    			<div class="panel panel-primary">
    			<div class="panel-body">
    				<h2>Device Rankings</h2>
    				<div id="alldevice-table">
    			    {$talldevicetable}
                    {$tpagination}
    		        </div>
    		    </div>
    			</div>
    		</div>
    PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
$tcurrpage = $_REQUEST["page"] ?? 1;
$tcurrpage = is_numeric($tcurrpage) ? $tcurrpage : 1;
$tsortmode = $_REQUEST["sortmode"] ?? "";
$tsortorder = $_REQUEST["sortorder"] ?? "asc";

$tpagetitle = "Device Rankings";
$tpage = new MasterPage($tpagetitle);
$timgdir = $tpage->getPage()->getDirImages();

// Build up our Dynamic Content Items.
$tpagelead = "";
$tpagecontent = createPage($timgdir, $tcurrpage, $tsortmode, $tsortorder);
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>
