<?php

/*
    jeer1902 26/4-23
    project: Warframe in-game status application
*/

/*
    a function that redirects the user to 'register_login.php' together whith 'php_error' argument value set as the arugument when this function was called.
    
    in case of this overloaded(the only defined function-overload) function 'refuse_submit' gets called, 
    the controlflow controling the submitted register credits did fore some reason not accept the credits.
    
*/

function refuse_submit ($reason){
    header("location:register_login.php?php_error=".$reason);
    exit();
}

/*
    a function that compares the arugument with a set of valid gameplatforms.
    
    in case of the argument matches any of the valid gameplatforms 'true' gets returned, else 'false' gets returned
    
*/

function is_a_valid_gameplatform($gameplatform_from_user){
    $valid_platforms = array("pc", "ps4", "xb1", "swi");
    if(in_array($gameplatform_from_user, $valid_platforms)){return true;}
    return false;
}

/*
    a controllflow that register and loging in a new user(if sucsessfully) based on the submitted credits from ther form in 'register_login.php'.

    in case of 'register_login.php'(in of wich this php script composes) is called with 'submited_register' arguments this control-flow gets executed.
*/

if(!empty($_REQUEST["submited_register"])){
    $unsafe_username = $_REQUEST["anv"];
    $unsafe_password = $_REQUEST["lsn"];
    $unsafe_gameplatform_if_usermodified = $_REQUEST["GamePlatform"];

    /*
        1. sanitize, and if anything gets removed refuse submission and inform user, header to prev php with post data
    */

    //sanitized user-values, filters out some chars that can create html tags and more
    $username = strip_tags($unsafe_username);
    $password = strip_tags($unsafe_password);
    $gameplatform = strip_tags($unsafe_gameplatform_if_usermodified);
    $username = filter_var($username, FILTER_SANITIZE_EMAIL); 
    $password = filter_var($password, FILTER_SANITIZE_EMAIL);
    $gameplatform = filter_var($gameplatform, FILTER_SANITIZE_EMAIL); 
    $username = filter_var($username, FILTER_SANITIZE_STRING); 
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $gameplatform = filter_var($gameplatform, FILTER_SANITIZE_STRING);

    
    if(($username != $unsafe_username) || ($password != $unsafe_password)){
        // the sanitation process changed the string wich means that the string is a potential danger to this application => refuse the submission.
        
        refuse_submit("unvalid_string_from_user");
    }
    if(!is_a_valid_gameplatform($gameplatform)){
        // the sanitation process changed the string wich means that the string is a potential danger to this application => refuse the submission.
        
        refuse_submit("Please select one of the given platforms. Have you been modifying the HTML-elements?");
    }

    /*
        2. if step 1 is compleated match username with database if avaliable, refuse submission if username arealy exists
    */

    $db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx') or refuse_submit("cannot_connect_to_database"); //or if mysqli_connect returned false

    $sql = "SELECT * FROM users where username='".$username."';";
    $result_x = mysqli_query($db, $sql);
    if(!$result_x) {
        //query did not sucseeded

        mysqli_close($db);
        refuse_submit("sql_query_error_contact_admin");
    }
    if(mysqli_num_rows($result_x) != 0){
        //empty set was not returned => the username(keyattribute) arealy exist in the database

        mysqli_close($db);
        refuse_submit("username_is_arealy_taken");
    }

    /*
        3. if step 2 is compleated, register the new user with a default profile and log in the user
    */

    $sql = "INSERT INTO users VALUES('".$username."', '".$password."', '".$gameplatform."');";
    $result_x = mysqli_query($db, $sql);
    if(!$result_x) {
        //query did not sucseeded

        mysqli_close($db);
        refuse_submit("sql_query_error_contact_admin");
    }

    mysqli_close($db);

    //assign session variables and header to index.php

    $_SESSION["loged_in_userprofile"] = $username;
    $_SESSION["gameplatform"] = $gameplatform;
    $_SESSION["sess_name"] = session_id();
    $_SESSION["expire"] = time() + (60 * 60); //1 hour expire time
    header("location:index.php");
    exit();

}

?>