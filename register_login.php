<?php

/*
jeer1902 23/4-23
project: Warframe in-game status application
*/

//page title and including 'login_header' which containing 'head' tag and an 'header' tag 
$page_title = "register";
include("includes/login_header.php");

//php script that handles the submitted credits from 'submitted_register' form
include("includes/submitted_from_register.php");
?>


<div class="rows"><!--each centreplaced-elements in 'login_block' has its own row -->

    <div class="centre_place"><!-- in this tag pull ewerything to its centre-->
	    <h2>Register a new user</h2>
    </div>
    <p>Allowed max lenght on username and password is 30 chars and no use of some special chars.</p>
    <?php
        // in case of 'submitted_from_register.php' algoritm refuse the submitted credits, this page with 'php_error' arguments will be called
        if(isset($_REQUEST["php_error"])){
            echo '<label>Could not create a new user bacause of: </label>'.$_REQUEST["php_error"];
        }
        
    ?>
    
    <!--javascript check if enterd credids are done corectly -->
    <div class="centre_place">
        <form action="register_login.php" method="POST" id="submitted_register"> <!--change id, duplicate -->
            <div class="rows"> <!--each centreplaced-elements in form has its own row -->
                <div class="rows"> <!--row for selected elements inside of this tag -->
                    <p class="form_label" >Username: </p> <input type="text" name="anv" id="anv" class="textbox" placeholder="Enter an username..."/>
	            </div>
                <div class="rows">
                    <p class="form_label" >Game Platform: </p>
                    <select id="GamePlatform" name="GamePlatform" class="textbox"> 
                        <option value="">--Please choose an option--</option>
                        <option value="ps4">Playstation </option>
                        <option value="xb1">Xbox</option>
                        <option value="swi">Nintendo Switch</option>
                        <option value="pc">Pc </option>
                    </select>
                </div>
	            <div class="rows">
	                <p class="form_label" >Password:</p> <input type="password" name="lsn" id="lsn" class="textbox" placeholder="Enter a password..."/>
	            </div>
                <div class="rows">
	                <p class="form_label" >Confirm password:</p> <input type="password" name="rep-lsn" id="rep-lsn" class="textbox" placeholder="Enter the password again..."/>
	            </div>
                <div class="centre_place">
		            <button type="submit" name="submited_register" value="submited_register" class="btn">Register and Log in</button> 
                </div>
            </div>
        </form>
    </div>

    <div class="rows"><!--margins away from form -->
        <div class="centre_place">
            <label>Having an account? Login <a href="login_login.php">here</a></label>
        </div>
    </div>

</div>

</section> <!--login_block-->
<?php
//page footer
include("includes/footer.php");
?>
