<?php
/*
jeer1902 25/4-23
project: Warframe in-game status application
*/


/*
    a function that redirects the user to the same webpage together whith argument value set as the arugument when this function was called.
    
    in case of this overloaded(the only defined function-overload) function 'refuse_submit' gets called, 
    'index.php'(in of wich this php script composes) gets called with 'php_error_left_content_add_activity' arguments.
    
*/

function refuse_submit ($reason){
    header("location:index.php?php_error_left_content_add_activity=".$reason);
    exit(); //ensure(for all browsers) that this script dont continue to execute
}

/*
    a controllflow that adds the given value of the 'add_activity' request into(if sucsessfull) the database tables.

    in case of 'index.php' is called with 'add_Activity' arguments this control-flow gets executed.
*/

if(isset($_REQUEST["add_Activity"])){ //isset acepts int(0)
    $unsafe_url = $_REQUEST["New_Activity"];

    // sanitize

    $added_url = strip_tags($unsafe_url);
    $added_url = filter_var($added_url, FILTER_SANITIZE_EMAIL);
    $added_url = filter_var($added_url, FILTER_SANITIZE_STRING);

    // the sanitation process changed the string wich means that the string is a potential danger to this application => refuse the submission.

    if(($added_url != $unsafe_url)){
        refuse_submit("Please select one of the given activities. Have you been modifying the HTML-elements?");
    }

    // attempting to establish contact with the database, if unsucsessfull abort the add_activity process through the 'refuse_submit' function.

    $db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx') or refuse_submit("cannot_connect_to_database");

    //if the record gets returned the user can add the activity, in short the 'NOT IN' statements between the users selected activities and the avalible activities got satisfied.

    $sql ="SELECT activity.url, description FROM activity, user_activity WHERE user_activity.url=activity.url AND user_activity.url = '".$added_url."' AND user_activity.url NOT IN (SELECT url from has where username = '".$_SESSION["loged_in_userprofile"]."') ORDER BY description;";

    //performing the sql query on the database 

    $result_x = mysqli_query($db, $sql);

    //controllflow according to the result of the sql query

    if(!$result_x){
        mysqli_close($db);
        refuse_submit("Cannot execute the request because of: sql_query_error_contact_admin"); //is cause because of a mistake from devs of the formated string value of $sql or a change in the database
    }
    if(mysqli_num_rows($result_x) == 0){
        mysqli_close($db);
        refuse_submit("Please select one of the given activities. Have you been modifying the HTML-elements?");
    }

    //free to add activity

    $sql = "INSERT INTO has(`username`, `url`) VALUES('".$_SESSION["loged_in_userprofile"]."', '".$added_url."');";
    $result_x = mysqli_query($db, $sql);
    if(!$result_x) {
        mysqli_close($db);
        refuse_submit("Cannot execute the request because of: sql_query_error_contact_admin"); // a way of letting the developers know if a developer-mistake occurd. This can be cause by typo in this script or a structural change in the database
    }

    mysqli_close($db);
    header("location:index.php"); //reload without argument values
    exit();
}

/*
    a controllflow that removes the given value of the 'delete_post' request off(if sucsessfull) the database tables.

    in case of 'index.php' is called with 'delete_post' arguments this control-flow gets executed.
*/

if(isset($_REQUEST["delete_post"])){ 
    $del_db_id = $_REQUEST["delete_post"];
    $db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx') or refuse_submit("cannot_connect_to_database");
    
    /*
        if delete del_db_id record dosent exist the query sucseeds but dosent affect the database. Was deleted somewere else before.
        if the query fails a boolean false gets returned from mysqli_query
    */

    $sql = "delete from has where username='".$_SESSION["loged_in_userprofile"]."' AND url='".$del_db_id."';";
    $result_x = mysqli_query($db, $sql);  
    if(!$result_x) {
        mysqli_close($db);
        refuse_submit("Cannot execute the request because of: sql_query_error_contact_admin");
    }

    mysqli_close($db);
    header("location:index.php"); 
    exit();
}

//retriving the user personaly selected activities

$retrive_activity_error_user = false;
$retrive_activity_error_user_message = "";
$user_activities = array();

$db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx'); 

/*
    a controllflow that retriving  activities from(if sucsessfull) the database tables that the user have selected to its profile.

    in case of sucsessfull extraction from the database the data is stored in some variables to be used later on in this webpage(in of wich this php script composes).
*/

if($db){
    //sucsessfully established connection wit database

    $sql = "SELECT activity.url, description FROM has, activity, user_activity WHERE username='".$_SESSION["loged_in_userprofile"]."' AND user_activity.url=has.url AND user_activity.url=activity.url ORDER BY description;";
    $mainbar_section_array_2 = array(); 
    $result = mysqli_query($db, $sql); //if empty the query will still sucseed
    if($result){ 
        //query sucseeded
        
        while ($row = mysqli_fetch_array($result)){ 
            //a new database record was extracted

            $activity = array();

            array_push($activity, $row['url']); 
            array_push($activity, $row['description']); 

            //store ascosiated attritbutes for the activity

            $attribute_collection = array();
            $sql = "SELECT json_index, description_before_data FROM attribute WHERE attribute.url='".$row['url']."' ORDER BY position_in_list;"; 
            $result_x = mysqli_query($db, $sql);
            if($result_x){
                //query sucseeded

                while ($row_2 = mysqli_fetch_array($result_x)){
                    //the activity have ascosiated attributes

                    $attribute_with_details = array();
                    array_push($attribute_with_details, $row_2['json_index']); 
                    array_push($attribute_with_details, $row_2['description_before_data']); 
                    array_push($attribute_collection, $attribute_with_details);
                }

                //all attributs collected for the activity

                array_push($activity, $attribute_collection);
            }
            else{
                //query did not sucseed

                $retrive_activity_error_user = true;
                $retrive_activity_error_user_message = "sql_query_error_contact_admin";
            }

            //all possible data for the activity was extracted from db 
            array_push($user_activities, $activity);
        }
    }
    else{
        //query did not sucseed

        $retrive_activity_error_user = true;
        $retrive_activity_error_user_message = "sql_query_error_contact_admin";
    }

    //If database connection was sucsessfully established it can be closed, independent of the queries sucsesson-status.

    mysqli_close($db);
}
else{
    //did not sucsessfully established connection wit database

    $retrive_activity_error_user = true;
    $retrive_activity_error_user_message = "cannot_connect_to_database";
}
?>

<h2>Activities personaly selected</h2> 
<?php  

    /*
        in case of add or remove activity algoritms refuse the submitted credits,
        'index.php'(in of wich this php script composes) with 'php_error_left_content_add_activity' arguments will be called.
    */

    if(isset($_REQUEST["php_error_left_content_add_activity"])){
        echo '<label>could not add requested activity bacause of: </label>'.$_REQUEST["php_error_left_content_add_activity"];
    }

    //something in the controllflow for extracting user selected activities set an error

    if($retrive_activity_error_user){
        echo '<label>following problem occurd when retriving your personal activities: </label>'.$retrive_activity_error_user_message;
    } 
    
?>
<div id="optional_error"></div><!-- location where javascript algoritms will print errors when building user selected activities -->

<!-- 
    A form were the user can select an activity to add to the set of user selected activities.

    The form can identify throug the enbedded php script wich activities the logged in user haven't selected yet in order to provide 
    correct options in the form to select between.
-->

<form action="index.php" method="GET" id="add_activity_form"> 
    <div class="rows"> <!--each centreplaced-elements in form has its own row -->
        <label class="centre_place" >Add Activity </label>
        <hr class='activity_separor' />
        <div class="centre_place">
            <select name="New_Activity" id="New_Activity" class="textbox"> 
            <?php
                //a controllflow that retriving  activities from(if sucsessfull) the database tables that the user not have selected yet.

                $db = mysqli_connect('studentmysql.miun.se', 'jeer1902', '50ya7gxl', 'jeer1902');
                if($db){
                    //sucsessfully established connection wit database

                    $sql ="SELECT activity.url, description FROM activity, user_activity WHERE user_activity.url=activity.url AND user_activity.url NOT IN (SELECT url from has where username = '".$_SESSION["loged_in_userprofile"]."') ORDER BY description;";//and if more users??
                    $result = mysqli_query($db, $sql);
                    if(!$result) {
                        //query did not sucseed

                        echo '<option value="case1">sql_query_error_contact_admin</option>';
                    }
                    else if(mysqli_num_rows($result) == 0){
                        //query sucseeded, returned empty set

                        echo '<option value="case2">All activities selected at the moment</option>';
                    }
                    else{ 
                        //query sucseeded

                        echo '<option value="">--Please choose an option--</option>';
                        while ($row = mysqli_fetch_array($result)){
                            echo "<option value=".$row['url'].">".$row['description']."</option>";
                        }
                    }

                    //If database connection was sucsessfully established it can be closed, independent of the queries sucsesson-status.

                    mysqli_close($db);

                }
                else{
                    //did not sucsessfully established connection wit database

                    echo '<option value="case3">cannot_connect_to_database</option>';
                }
                ?>
            </select>
        </div>
        <div class="rows"> <!--margins around button -->
            <div class="centre_place">
		        <button type="submit" name="add_Activity" value="add_Activity" class="btn">Add selected activity</button> 
            </div>
        </div>
    </div>
</form>
<div id="optional"></div> <!-- location where javascript algoritms will build and store the created user selected activities -->