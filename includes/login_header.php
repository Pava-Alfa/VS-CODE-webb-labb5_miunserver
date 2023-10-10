<?php

/*
jeer1902 24/4-23
project: Warframe in-game status application
*/

session_start(); //access to the session-variable
mysqli_report(MYSQLI_REPORT_OFF); //no exeptions is thrown from mysqli functions, the scripts handle the responses from the functions instead. This is for all login and register pages. 
error_reporting(0); //user can't see errors
include("includes/config.php"); //variables with website information

//arealy logged in user
//undefined variable error when == controleed

/*
    a controll-flow that controlls if the user is arealy logged in or not.
    If the session indexes storing logged in data is defined and valid the user is redirected to the logged in secton of the website.
    If the quterias above is not satisfied nothing happens.
*/

if(!empty($_SESSION["sess_name"]) && !empty($_SESSION["loged_in_userprofile"])){
        if($_SESSION["sess_name"] == session_id()){ //if the client changes session_ID
                header("location:index.php");
        }
} 
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
                <div id="block2"><h1>Warframe in-game status application</h1></div>
                <div id="block3"></div>
        </header>
        <section id="login_block">
        