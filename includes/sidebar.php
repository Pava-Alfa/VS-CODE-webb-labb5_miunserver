<!--
jeer1902 25/4-23
project: Warframe in-game status application
-->

</section><!-- leftcontent -->

<?php
//retriving the preselected activities

$retrive_activity_error_sidebar = false;
$retrive_activity_error_sidebar_message = "";
$sidebar_array_2 = array(); 

$db = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx');

/*
    a controllflow that retriving  activities from(if sucsessfull) the database tables that is set to be displayed in the sidebar.

    in case of sucsessfull extraction from the database the data is stored in some variables to be used later on in this webpage(in of wich this php script composes).
*/

if($db){
    //sucsessfully established connection wit database

    $sql = "SELECT activity.url, description FROM sidebar_activity, activity WHERE sidebar_activity.url=activity.url;";
    $result_x = mysqli_query($db, $sql);

    if($result_x){
        //query sucseeded

        while ($row = mysqli_fetch_array($result_x)){
            //a new database record was extracted

            $activity = array();

            array_push($activity, $row['url']); 
            array_push($activity, $row['description']); 

            //store ascosiated attritbutes for the activity

            $attribute_collection = array();
            $sql = "SELECT json_index, description_before_data FROM attribute WHERE attribute.url='".$row['url']."' ORDER BY position_in_list;"; 
            $result_y = mysqli_query($db, $sql);

            if($result_y){
                //query sucseeded

                while ($row_2 = mysqli_fetch_array($result_y)){
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
                $retrive_activity_error_sidebar = true;
                $retrive_activity_error_sidebar_message = "sql_query_error_contact_admin";
            }

            //all possible data for the activity was extracted from db 

            array_push($sidebar_array_2, $activity);
        }
    }
    else{
        $retrive_activity_error_sidebar = true;
        $retrive_activity_error_sidebar_message = "sql_query_error_contact_admin";
    }

    //If database connection was sucsessfully established it can be closed, independent of the queries sucsesson-status.

    mysqli_close($db);
}
else{
    //did not sucsessfully established connection wit database

    $retrive_activity_error_sidebar = true;
    $retrive_activity_error_sidebar_message = "cannot_connect_to_database";
}

?>
<section id="sidebar">
    <h2>Activities preselected</h2>
    <?php

    //something in the controllflow for extracting preselected activities set an error

    if($retrive_activity_error_user){
        echo '<label>following problem occurd when retriving preselected activities: </label>'.$retrive_activity_error_sidebar_message;
    } 

    ?>
    <div id="preselected_error"></div><!-- location where javascript algoritms will print errors when building preselected activities -->
    <div id="preselected"></div><!-- location where javascript algoritms will build and store the created preselected activities -->

</section>

</div><!--grid2-->