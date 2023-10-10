<?php
/*
jeer1902 26/4-23
project: Warframe in-game status application
*/

/*
    a function that redirects the user to 'login_login.php' together whith 'php_error' argument value set as the arugument when this function was called.
    
    in case of this overloaded(the only defined function-overload) function 'refuse_submit' gets called, 
    the controlflow controling the submitted log in credits did fore some reason not accept the credits.
    
*/

function refuse_submit ($reason){ 
    header("location:login_login.php?php_error=".$reason);
    exit();
}

/*
    a controllflow that  loging in an user(if sucsessfully) based on the submitted credits from ther form in 'login_login.php'.

    in case of 'login_login.php'(in of wich this php script composes) is called with 'Submited_log_in' arguments this control-flow gets executed.
*/

if(!empty($_REQUEST["Submited_log_in"])){
    $unsafe_username = $_REQUEST["login_anv"];
    $unsafe_password = $_REQUEST["login_lsn"];

    /*
        1. sanitize, and if anything gets removed refuse submission and inform user, header to prev php with post data
    */

    //sanitized user-values, filters out some chars that can create html tags and more
    $username = strip_tags($unsafe_username);
    $password = strip_tags($unsafe_password);
    $username = filter_var($username, FILTER_SANITIZE_EMAIL); 
    $password = filter_var($password, FILTER_SANITIZE_EMAIL); 
    $username = filter_var($username, FILTER_SANITIZE_STRING); 
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    
    if(($username != $unsafe_username) || ($password != $unsafe_password)){
        // the sanitation process changed the string wich means that the string is a potential danger to this application => refuse the submission.

        refuse_submit("unvalid_string_from_user");
    }

    /*
        2. if step 1 is compleated match username with database, and if not found, refuse submission.
    */
    
    $db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx') or refuse_submit("cannot_connect_to_databases"); //or if mysqli_connect returned false


    $sql = "SELECT * FROM users where username='".$username."';";
    $result_x = mysqli_query($db, $sql);
    if(!$result_x) {
        //query did not sucseeded

        mysqli_close($db);
        refuse_submit("sql_query_error_contact_admin");
    }
    if(mysqli_num_rows($result_x) == 0){ //or typecasted
        //empty set was returned

        mysqli_close($db);
        refuse_submit("username_is_not_registerd");
    }

    /*
        3. if step 2 is compleated, validate the password
    */

    $user = mysqli_fetch_array($result_x); //the user from database that matched the submitted usename

    if($password != $user['password']){
        mysqli_close($db);
        refuse_submit("wrong_password_asociated_with_username");
    }

    mysqli_close($db);

    //assign session variables and header to index.php
    
    $_SESSION["loged_in_userprofile"] = $user['username'];
    $_SESSION["sess_name"] = session_id();
    $_SESSION["gameplatform"] = $user['gameplatform'];
    $_SESSION["expire"] = time() + (60 * 60); //expire in 1 hour
    header("location:index.php");
    exit();

}
?>