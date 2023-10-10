<?php

/*
jeer1902 23/4-23
project: Warframe in-game status application
*/

//page title and including 'login_header' which containing 'head' tag and an 'header' tag 
$page_title = "login";
include("includes/login_header.php");

//php script that handles the submitted credits from 'submitted_login' form
include("includes/submitted_from_login.php");
?>

<div class="rows"><!--each centreplaced-elements in 'login_block' has its own row -->

    <div class="centre_place"><!-- in this tag pull ewerything to its centre-->
	    <h2>Enter a registred username and password</h2>
    </div>
    <p>Allowed max lenght on username and password is 30 chars and no use of some special chars.</p>
    <?php
        // in case of 'submitted_from_login.php' algoritm refuse the submitted credits, this page with 'php_error' arguments will be called
        if(isset($_REQUEST["php_error"])){
            echo '<label>could not log in bacause of: </label>'.$_REQUEST["php_error"];
        }
    ?>
    
    <div class="centre_place">
    <form action="login_login.php" method="POST" id="submitted_login">
        <div class="rows"> <!--each centreplaced-elements in form has its own row -->
            <div class="rows"> <!--row for selected elements inside of this tag -->
                <p class="form_label" >Username: </p> <input type="text" name="login_anv" id="login_anv" class="textbox" placeholder="Enter your username..." />
	        </div>
	        <div class="rows">
	            <p class="form_label" >Password:</p> <input type="password" name="login_lsn" id="login_lsn" class="textbox" placeholder="Enter your password..."/>
	        </div>
            <div class="centre_place">
		        <button type="submit" name="Submited_log_in" value="submited_log_in" class="btn" >Log in</button> 
            </div>
        </div>
    </form>
    </div>

    <div class="rows"><!--margins away from form -->
        <div class="centre_place">
            <label>New user? Register <a href="register_login.php">here</a></label>
        </div>
    </div>

</div>

</section> <!--login_block-->
<?php
//page footer
include("includes/footer.php");
?>