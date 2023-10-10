<?php

/*
jeer1902 23/4-23
project: Warframe in-game status application
*/

//page title and including 'login_header' which containing 'head' tag and an 'header' tag
$page_title = "start page";
include("includes/login_header.php");

//redirects from this page to either the page dedicated to register-and-login or just login
if(isset($_REQUEST["Log_in"])){
	header("location:login_login.php");
	exit();
}

if(isset($_REQUEST["Register_new_user"])){
	header("location:register_login.php");
	exit();
}
?>


<div class="rows"><!--each centreplaced-elements in 'login_block' has its own row -->
	<div class="centre_place"><!-- in this tag pull ewerything to its centre-->
		<h2>Welcome, Is it your first time here or are you arealy registred?</h2>
	</div>
	<?php
		// in case of sessions expire, index will header to this page together with 'session_info' arguments
		if(isset($_REQUEST["session_info"])){
		echo '<p>'.$_REQUEST["session_info"].'</p>';
		}
	?>

	<div class="centre_place">
		<form action="startpage.php" method="GET" class="login_form"> 
			<div class="rows"> <!--each centreplaced-elements in form has its own row -->
				<div class="rows">
					<button type="submit" name="Log_in" value="Log_in" class="btn">Log in</button>
				</div>
				<div class="rows">
					<button type="submit" name="Register_new_user" value="Register_new_user" class="btn">Register new user</button> 
				</div>
			</div>
		</form>
	</div>
</div>

</section> <!--login_block-->
<?php

//page footer
include("includes/footer.php");
?>