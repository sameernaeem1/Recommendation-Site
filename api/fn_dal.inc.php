<?php
// Include the Other Layers Class Definitions
require_once ("oo_bll.inc.php");
require_once ("oo_pl.inc.php");

// ---------JSON HELPER FUNCTIONS-------------------------------------------------------
function jsonOne($pfile, $pid)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek($pid - 1);
    $tdata = json_decode($tsplfile->current());
    return $tdata;
}

function jsonAll($pfile)
{
    $tentries = file($pfile);
    $tarray = [];
    foreach ($tentries as $tentry) {
        $tarray[] = json_decode($tentry);
    }
    return $tarray;
}

function jsonNextID($pfile)
{
    $tsplfile = new SplFileObject($pfile);
    $tsplfile->seek(PHP_INT_MAX);
    return $tsplfile->key() + 1;
}

// ---------ID GENERATION FUNCTIONS-------------------------------------------------------
function jsonNextUserID()
{
    return jsonNextID("data/json/users.json");
}

function jsonNextUserReviewID()
{
    return jsonNextID("data/json/userReviews.json");
}

function jsonNextUserScoreID()
{
    return jsonNextID("data/json/userscores.json");
}

// ---------JSON-DRIVEN OBJECT CREATION FUNCTIONS-----------------------------------------
function jsonLoadOneDevice($pid): BLLDevice
{
    $tdevice = new BLLDevice();
    $tdevice->fromArray(jsonOne("data/json/devices.json", $pid));
    return $tdevice;
}

function jsonLoadOneUserReview($pid): BLLUserReviews
{
    $tuserreview = new BLLUserReviews();
    $tuserreview->fromArray(jsonOne("data/json/devices.json", $pid));
    return $tuserreview;
}

// --------------MANY OBJECT IMPLEMENTATION--------------------------------------------------------
function jsonLoadAllDevice(): array
{
    $tarray = jsonAll("data/json/devices.json");
    return array_map(function ($a) {
        $tc = new BLLDevice();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

function jsonLoadAllUserReviews(): array
{
    $tarray = jsonAll("data/json/userReviews.json");
    return array_map(function ($a) {
        $tc = new BLLUserReviews();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

function jsonLoadAllUserScores(): array
{
    $tarray = jsonAll("data/json/userscores.json");
    return array_map(function ($a) {
        $tc = new BLLUserScores();
        $tc->fromArray($a);
        return $tc;
    }, $tarray);
}

// ---------XML HELPER FUNCTIONS--------------------------------------------------------
function xmlLoadAll($pxmlfile, $pclassname, $parrayname)
{
    $txmldata = simplexml_load_file($pxmlfile, $pclassname);
    $tarray = [];
    foreach ($txmldata->{$parrayname} as $telement) {
        $tarray[] = $telement;
    }
    return $tarray;
}

function xmlLoadOne($pxmlfile, $pclassname)
{
    $txmldata = simplexml_load_file($pxmlfile, $pclassname);
    return $txmldata;
}

?>