<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
$tusername = $_REQUEST["username"] ?? "";
$tlogintoken = $_SESSION["myuser"] ?? "";

if (empty($tlogintoken) && ! empty($tusername)) {
    $_SESSION["myuser"] = processRequest($tusername);
    $_SESSION["entered"] = true;
    header("Location: index.php");
} else {
    $terror = "app_error.php";
    header("Location: {$terror}");
}

?>