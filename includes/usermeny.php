<?php
/*
jeer1902 24/4-23
project: Warframe in-game status application
*/

// in case of 'Log Out' anchor tag get pressed, 'index.php'(in of wich this php script composes) gets called with 'log_out' arguments.

if(isset($_REQUEST["log_out"])){
    session_destroy(); //unset all indexes at the same time
    header("location:startpage.php");
    exit(); //ensure(for all browsers) that this script dont continue to execute
}
?>

<nav id="usermenu">
    <ul>
        <li class="usermenu_margins">Logged in as: <?= $_SESSION["loged_in_userprofile"]; ?></li>
        <li class="usermenu_margins">Platform: <label id="platform"> <?= $_SESSION["gameplatform"]; ?> </label></li>
        <li class="usermenu_margins"><a href="index.php?log_out=1">Log out</a></li>
    </ul>
</nav>