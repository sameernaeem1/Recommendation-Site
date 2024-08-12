<?php
require_once ("oo_bll.inc.php");
require_once ("oo_pl.inc.php");

// ===========RENDER BUSINESS LOGIC OBJECTS=======================================================================
function renderDeviceAsSummary(BLLDevice $pitem)
{
    $titemsrc = ! empty($pitem->deviceimage_href) ? $pitem->deviceimage_href : "blank-thumb.jpg";
    $oslink = "";
    if ($pitem->operatingsystem == "iOS 16") {
        $oslink .= <<<OS
                    <h3>Operating System: <a href="operatingSystem.php">{$pitem->operatingsystem}</a></h3>
        OS;
    } else {
        $oslink .= <<<OS
                    <h3>Operating System: {$pitem->operatingsystem}</h3>
        OS;
    }
    $tdeviceitem = <<<NI
    		    <section class="row details clearfix">
    		    <div class="media-left media-top">
    				<img src="img/devices/{$titemsrc}" width="176" height="260" />
    			</div>
    			<div class="media-body">
    				<h2><strong>{$pitem->devicename}</strong></h2>
    				
    				<div class="ni-summary">
    				<h3>Manufacturer: {$pitem->manufacturer}</h3>
    				<h3>Release Date: {$pitem->releasedate}</h3>
    				{$oslink}
    				</div>
                    <a class="btn btn-info" href="device.php?deviceid={$pitem->id}">Full Review</a>
    	        </div>
    			</section>
    NI;
    return $tdeviceitem;
}

function renderDeviceTable(BLLallDevices $palldevices)
{
    // $tavg = "";
    // $tscores = jsonLoadAllUserScores();
    // foreach ($tscores as $tscore) {
    // $tavg .= $tscore->devicerating;
    // }
    $trowdata = "";
    foreach ($palldevices->devicelist as $tp) {
        $trowdata .= <<<ROW
        <tr>
           <td>{$tp->devicename}</td>
           <td>{$tp->cpucores}</td>
           <td>{$tp->ram}</td>
           <td>{$tp->battery}</td>
           <td>{$tp->recommendationscore}</td>
           <td><a class="btn btn-info" href="device.php?deviceid={$tp->id}">Device Page</a></td>
        </tr>
        ROW;
    }
    $ttable = <<<TABLE
    <table class="table table-striped table-hover">
    	<thead>
    		<tr>
    			<th id="sort-device">Device</th>
    			<th id="sort-cores">CPU Cores</th>
    			<th id="sort-ram">RAM</th>
    			<th id="sort-battery">Battery</th>
    			<th id="sort-review">Review Score</th>
    			<th> </th>
    		</tr>
    	</thead>
    	<tbody>
    	{$trowdata}
    	</tbody>
    </table>
    TABLE;
    return $ttable;
}

function renderDeviceOverview(BLLdevice $pdevice)
{
    $tuserreviews = jsonLoadAllUserReviews();
    $flag = false;
    $previews = "";
    foreach ($tuserreviews as $trev) {
        if ($trev->deviceid == $pdevice->id) {
            $flag = true;
            $previews .= <<<REVIEWS
                <div class="well">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>{$trev->reviewtitle} &emsp;  {$trev->devicerating}/10</strong>
                        </li>
                        <li class="list-group-item">
                            {$trev->reviewdesc}
                        </li>
                        <li class="list-group-item">
                            By {$trev->firstname} {$trev->lastname}
                        </li>
                    </ul>
                </div>
            REVIEWS;
        }
    }
    if ($flag == false) {
        $previews .= <<<NOREVIEWS
                    <h3><u>There are no user reviews for this device yet.</u></h3>
        NOREVIEWS;
    }

    $review = file_get_contents("data/html/{$pdevice->review_href}");
    $tdevicehtml = <<<OV
        <h2><center><strong>{$pdevice->devicename}</strong></center></h2>
        <img src="img/devices/{$pdevice->deviceimage_href}" width="312" height="480"  style="float: left; margin-right: 20px"/>
        <h3>Manufacturer: {$pdevice->manufacturer}</h3>
        <h3>Release Date: {$pdevice->releasedate}</h3>
        <h3>Operating System: {$pdevice->operatingsystem}</h3>
        <h3>Battery: {$pdevice->battery}</h3>
        <h3>RAM: {$pdevice->ram}</h3>
        <h3>CPU Cores: {$pdevice->cpucores}</h3>
        <h3>Chip: {$pdevice->chip}</h3>
        <h3>Camera: {$pdevice->camera}</h3>
        <h3>Dimensions: {$pdevice->dimensions}</h3>
        <h3>Weight: {$pdevice->weight}</h3>
        <h3>Recommendation Score: {$pdevice->recommendationscore}</h3>
        <h3><strong>Review</strong></h3>
        <h3>{$review}</h3>
        <br>
        <h3><strong>External Reviews you should check out</strong></h3>
        <h3><a href ="{$pdevice->ext1}">{$pdevice->ext1name}</a></h3>
        <h3><a href ="{$pdevice->ext2}">{$pdevice->ext2name}</a></h3>
        <h3><a href ="{$pdevice->ext3}">{$pdevice->ext3name}</a></h3>
        <br>
        <h3><strong>Where you can buy</strong></h3>
        <h3><a href ="{$pdevice->buy1link}">{$pdevice->buy1}</a> &emsp; Price: {$pdevice->price1}</h3>
        <h3><a href ="{$pdevice->buy2link}">{$pdevice->buy2}</a> &emsp; Price: {$pdevice->price2}</h3>
        <h3><a href ="{$pdevice->buy3link}">{$pdevice->buy3}</a> &emsp; Price: {$pdevice->price3}</h3>
        <br>
        <h3><strong>User Reviews</strong></h3>
        {$previews}
        <h3>Click <a href="createReview.php"> here</a> to write your own review.</h3>
    OV;
    return $tdevicehtml;
}

// ------Tried to use these functions to embed youtube videos into operating system overview but didn't work-----------------
function getYoutubeEmbeddedURL($url)
{
    return "https://www.youtube.com/embed/" . getYoutubeID($url);
}

function getYoutubeID($url)
{
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $params);
    if (isset($params['v']) && strlen($params['v']) > 0) {
        return $params['v'];
    } else {
        return '';
    }
}

// -------------------------------------------------------------------------------------------------------------------------
function renderUITabs(array $ptabs, $ptabid)
{
    $count = 0;
    $ttabnav = "";
    $ttabcontent = "";
    $ttabvid = "";

    foreach ($ptabs as $ttab) {
        $tnavactive = "";
        $ttabactive = "";
        if ($count === 0) {
            $tnavactive = " class=\"active\"";
            $ttabactive = " active in";
        }
        $ttabnav .= "<li{$tnavactive}><a href=\"#{$ttab->tabid}\" data-toggle=\"tab\">{$ttab->tabname}</a></li>";
        $ttabcontent .= "<article class=\"tab-pane fade{$ttabactive}\" id=\"{$ttab->tabid}\">{$ttab->content}</article>";

        $count ++;
    }

    $ttabhtml = <<<HTML
            <ul class="nav nav-tabs">
            {$ttabnav}
            </ul>
        	<div class="tab-content" id="{$ptabid}">
    			  {$ttabcontent}
                  {$ttabvid}
    		</div>
    HTML;
    return $ttabhtml;
}

function renderPagination($ppage, $pno, $pcurr)
{
    if ($pno <= 1)
        return "";

    $titems = "";
    $tld = $pcurr == 1 ? " class=\"disabled\"" : "";
    $trd = $pcurr == $pno ? " class=\"disabled\"" : "";

    $tprev = $pcurr - 1;
    $tnext = $pcurr + 1;

    $titems .= "<li$tld><a href=\"{$ppage}?page={$tprev}\">&laquo;</a></li>";
    for ($i = 1; $i <= $pno; $i ++) {
        $ta = $pcurr == $i ? " class=\"active\"" : "";
        $titems .= "<li$ta><a href=\"{$ppage}?page={$i}\">{$i}</a></li>";
    }
    $titems .= "<li$trd><a href=\"${ppage}?page={$tnext}\">&raquo;</a></li>";

    $tmarkup = <<<NAV
        <ul class="pagination pagination-sm">
            {$titems}
        </ul>
    NAV;
    return $tmarkup;
}

?>