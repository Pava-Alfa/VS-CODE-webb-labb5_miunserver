<?php
/*
jeer1902 23/4-23
project: Warframe in-game status application
*/

include("includes/config.php"); //variables with website information
session_start(); //access to the session-variable
mysqli_report(MYSQLI_REPORT_OFF); //no exeptions is thrown from mysqli functions, the scripts handle the responses from the functions instead. This is for the pages when a user is loged in. Try-catch would not give same page apperance as disired.
error_reporting(0); //user can't see errors

/*
    a function that compares the stored exprire time of the current session with the current time.
    If the exparation timestamp is before the returned timestamp from 'time' function, then nothing happens.
    If the exparation timestamp is equal or after the returned timestamp from 'time' function, then all session indexes gets erased and user is redirected to the statpage.
*/

function validate_session_expire(){
    if(time() > $_SESSION["expire"]){
        session_destroy(); //unset all indexes at the same time
        header("location:startpage.php?session_info=Session expired. please login again.");
        exit(); //ensure(for all browsers) that this script dont continue to execute
    }
}

//login controll if the user directly put the url to this webpage or if the client session ID has changed
if(!empty($_SESSION["sess_name"]) && !empty($_SESSION["loged_in_userprofile"])){ //undefined variable error when == controleed, also check if this lab is used to not mix with oter lab
    if($_SESSION["sess_name"] != session_id()){ 
    header("location:startpage.php");
    exit();
    }
}
else{
    header("location:startpage.php");
    exit(); 
}

validate_session_expire(); //in cases when users try to modifi the state of the profile, such when a activity is added or removed the sesson needs to be valid
?>

<!DOCTYPE html>
<html lang="sv">
<head>
<title><?= $site_title . $divider . $page_title; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/stilmallen.css" type="text/css">
</head>
<body>
        <header id="mainheader">
                <div id="block1"></div>
                <div id="block2">
                    <h1>Warframe in-game status application</h1>
                </div>
                <div id="block3">
                <!--personal user control panel -->
                <?php include("includes/usermeny.php"); ?>
                </div>
        </header>
        <div id="grid2">
        <section id="leftcontent">