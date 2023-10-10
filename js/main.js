/*
    jeer1902 23/4-23
    project: Warframe in-game status application
*/

"use strict";

var baseURL = "https://api.warframestat.us/";
var sidebar_array = []; //container for  sidebar-activities  data recived from php-database 
var main_section_array = []; //container for  user-selected-activities  data recived from php-database 
var sidebar_activities_set_from_db = false;
var main_section_activities_set_from_db = false;

//global function that the php script can call with an json-encoded array as argument
function write_in_sidebar(data){
    sidebar_activities_set_from_db = true; //if nothing is submitted fom server database the printer wserves no purpose to run at the moment
    sidebar_array = data;
}

//global function that the php script can call with an json-encoded array as argument
function write_in_main_section(data){
    main_section_activities_set_from_db = true; //if nothing is submitted fom server database the printer wserves no purpose to run at the moment
    main_section_array = data;
}

document.addEventListener("DOMContentLoaded", function() {

    //when the content is loaded thease if statements checke if the elements with the specific id exist in dom-tree.
    //This varies on which webpage the user is in, if this is the case the related content is executed and not if document.getelementbyid returns false.

    if(document.getElementById("sidebar")){ 

        //defined as async in order to use "await" statement
        async function update_sidebar_from_db(){
            
            //without the db values the adress variable will not be assingned a correct fatch adress for the api => fetch error 404 from the restapi
            if(sidebar_activities_set_from_db){
        
                let platform = document.getElementById("platform").innerHTML.trim(); //trim whitespaces
                let baseURL_platform = baseURL+platform+"/";
        
                //clears previous printed activities(outdated) and errors(outdated) to be replaced by up-to-date ones.
                document.getElementById("preselected").innerHTML = "";
                document.getElementById("preselected_error").innerHTML = "";
                
                //loop trough each activity recived from db 
                for(var index = 0; index < sidebar_array.length; index++){
                    let adress = baseURL_platform+sidebar_array[index][0]; //activity url
                    let activity = sidebar_array[index][1]; //name of activity
                    let attributes_array = sidebar_array[index][2]; // selected attributes from activity
                    
                    //the activities corresopnding JSON-object or JSON-notaded array gets fetched in the order of elements in the 'sidebar_arry' one at the time  by using 'await'.
                    await fetch(adress, {method: 'GET'}) //the activities dont race to be the first one in the sidebar by using 'await'. 
                    .then(response => response.json())
                    .then(jsonData => {
                        var generated_activity = "<div class='activity'>"; //block for activity
                   
                        generated_activity += "<p >"+activity+"</p>";
                        generated_activity += "<hr class='activity_separor' />";
                        if(jsonData.id){
                            //JSON object with values recived
                        
                            /*
                                get attributes values from the JSON-object
                                if 'attributes_array' is empty nothing happens here
                            */

                            attributes_array.forEach(attribute_in_json => {
                                generated_activity += "<p>"+attribute_in_json[1]+jsonData[attribute_in_json[0]]+"</p>";
                            });
                        }
                        else if(jsonData.length != 0){
                            //JSON array with values recived

                            /*
                                for each json object in the JSON-array, get its attributes values
                                if 'attributes_array' is empty nothing happens here
                            */

                            jsonData.forEach(array_element => {
                                attributes_array.forEach(attribute_in_json => {
                                generated_activity += "<p>"+attribute_in_json[1]+array_element[attribute_in_json[0]]+"</p>";
                                });
                                generated_activity += "<hr class='activity_separor' />";
                            });
                        }
                        else{
                            //Empty JSON array recived. Indicates that the api url is valid and defined but without data at the moment

                            generated_activity += "<p>No avaliable data at the moment</p>";
                        }
                    
                        generated_activity += "</div>";
                        document.getElementById("preselected").innerHTML += generated_activity; //adds the activity to the list of all activities in the sidebar
                    
                   
                    }).catch(error => {
                        //prints the errors of the tempoarly batch(up-to-date) of the activities. Thease errors are alongside the current batch in the sidebar.  
                        document.getElementById("preselected_error").innerHTML += "<p>There was an error in the preselected activities "+error+" at "+activity+"</p>"; 
                    });
                }
            }
        }
        update_sidebar_from_db() //calls after 0 seconds
        setInterval(function(){update_sidebar_from_db()}, 30000) //calls continues each 30 second. The first call is made after 30 seconds, thus the extra call above this row.
    }
    if(document.getElementById("leftcontent")){ 
        async function update_main_section_from_db(){
            //without the db values the adress variable will not be assingned a correct fatch adress for the api => fetch error 404 from the restapi
            if(main_section_activities_set_from_db){
        
                let platform = document.getElementById("platform").innerHTML.trim(); //trim whitespaces
                let baseURL_platform = baseURL+platform+"/";
        
                //clears previous printed activities(outdated) and errors(outdated) to be replaced by up-to-date ones.
                document.getElementById("optional").innerHTML = "";
                document.getElementById("optional_error").innerHTML = "";

                //loop trough each activity recived from db
                for(var index = 0; index < main_section_array.length; index++){
                    let adress = baseURL_platform+main_section_array[index][0]; //activity url
                    let activity = main_section_array[index][1]; //name of activity
                    let attributes_array = main_section_array[index][2]; // selected attributes from activity
                    
                    //the activities corresopnding JSON-object or JSON-notaded array gets fetched in the order of elements in the 'main_section_array' one at the time  by using 'await'.
                    await fetch(adress, {method: 'GET'}) //the activities dont race to be the first one in the list of user selected activities(the main-section). 
                    .then(response => response.json())
                    .then(jsonData => {
                        var generated_activity = "<div class='activity'>"; //block for activity

                        generated_activity += '<form action="index.php" method="post">';
                   
                        generated_activity += "<p>"+activity+"</p>";
                        generated_activity += "<hr class='activity_separor' />";
                        if(jsonData.id){
                            //JSON object with values recived
                        
                            /*
                                get attributes values from the JSON-object
                                if 'attributes_array' is empty nothing happens here
                            */
                            attributes_array.forEach(attribute_in_json => {
                                generated_activity += "<p>"+attribute_in_json[1]+jsonData[attribute_in_json[0]]+"</p>";
                            });
                        }
                        else if(jsonData.length != 0){
                            //JSON array with values recived

                            /*
                                for each json object in the JSON-array, get its attributes values
                                if 'attributes_array' is empty nothing happens here
                            */

                            jsonData.forEach(array_element => {
                                attributes_array.forEach(attribute_in_json => {
                                generated_activity += "<p>"+attribute_in_json[1]+array_element[attribute_in_json[0]]+"</p>";
                                });
                                generated_activity += "<hr class='activity_separor' />";
                            });
                        }
                        else{
                            //Empty JSON array recived. Indicates that the api url is valid and defined but without data at the moment

                            generated_activity += "<p>No avaliable data at the moment</p>";
                        }
                        
                        //removal button embedded into the activity block
                        generated_activity += '<div class="centre_place">'
                        generated_activity += "<button type='submit' name='delete_post' value='"+main_section_array[index][0]+"' class='btn'>Remove activity</button>";
                        generated_activity += "</div>";
                        generated_activity += '</form>';
                    
                        generated_activity += "</div>";
                        document.getElementById("optional").innerHTML += generated_activity; //adds the activity to the list of all activities in the main-section
                   
                    }).catch(error => {
                        //prints the errors of the tempoarly batch(up-to-date) of the activities. Thease errors are alongside the current batch in the main-section.  
                        document.getElementById("optional_error").innerHTML += "<p>There was an error in the personal activities "+error+" at "+activity+"</p>";
                    });
                }
            }
        }
        update_main_section_from_db() //calls after 0 seconds
        setInterval(function(){update_main_section_from_db()}, 30000) //calls continues each 30 second. The first call is made after 30 seconds, thus the extra call above this row.

        /*
            a eventlistner fallback function that controls of a submission of the add activity form in the main section, to save traffic between client and webbserver.
            In case of not valid inputs the user will recive guidence through an alert messege on how to correct the mistake.
        */

        document.getElementById("add_activity_form").addEventListener("submit", (e) => {
            var form_indicator = document.getElementById("New_Activity").value; //the added unicue activity url-extention, a keyword.
            if(form_indicator == "case1"){
                //not valid option in the form, not a posible valid url extention keyword 
                alert('Cannot execute the request because of: previous sql_query_error_contact_admin');
                e.preventDefault();
                return false; //exit the fallback function
            }
            else if(form_indicator == "case2"){
                //not valid option in the form, not a posible valid url extention keyword
                alert('All activities selected at the moment');
                e.preventDefault();
                return false; 
            }
            else if(form_indicator == "case3"){
                //not valid option in the form, not a posible valid url extention keyword
                alert('Cannot execute the request because of: cannot_connect_to_database');
                e.preventDefault();
                return false; 
            }
            else if(form_indicator == ""){
                //not valid option in the form, not a posible valid url extention keyword
                alert('Please choose an option');
                e.preventDefault();
                return false; 
            }

            return true; //exit the fallback function
        })
    }
    
    if(document.getElementById("submitted_register")){ 
        
        /* 
            a eventlistner fallback function that controls of a submission of the registartion of new users in the registe login page, to save traffic between client and webbserver.
            In case of not valid inputs the user will recive guidence through an alert messege on how to correct the mistake.
        */

        document.getElementById("submitted_register").addEventListener("submit", (e) => {
            if(document.getElementById("anv").value == "" || document.getElementById("lsn").value == "" || document.getElementById("rep-lsn").value == "" || document.getElementById("GamePlatform").value ==""){
                //false, empty spaces, needs to give user status info on how to correct mistake. 
                alert('Please fill all the inputfields.');
                e.preventDefault();
                return false; //exit the fallback function
            }
            if(document.getElementById("lsn").value != document.getElementById("rep-lsn").value){
                //false, not correct confirmed password, needs to give user status info on how to correct mistake

                alert('Password and repeated password dosent match.');
                e.preventDefault();
                return false;
            }
            if(document.getElementById("anv").value.length > 30){
                //false, to long string exeding 30 chars

                alert('Username exceeds 30 chars');
                e.preventDefault();
                return false;
            }
            if(document.getElementById("lsn").value.length > 30){
                //false, to long string exeding 30 chars

                alert('Password exceeds 30 chars');
                e.preventDefault();
                return false;
            }
            if(document.getElementById("GamePlatform").value.length != 2 && document.getElementById("GamePlatform").value.length != 3){
                //false, user edited the html-element

                alert('Please select one of the given platforms. Have you been modifying the HTML-elements?');
                e.preventDefault();
                return false;
            }

            return true; //exit the fallback function
        })
    }

    if(document.getElementById("submitted_login")){
        
        /* 
            a eventlistner fallback function that controls of a submission of the login from arealy registred users in the registe login page, to save traffic between client and webbserver.
            In case of not valid inputs the user will recive guidence through an alert messege on how to correct the mistake.
        */

        document.getElementById("submitted_login").addEventListener("submit", (e) => {
        
            if(document.getElementById("login_anv").value == "" || document.getElementById("login_lsn").value == ""){
                //false, empty spaces, needs to give user status info on how to correct mistake. alerts??
                alert('Please fill all the inputfields.');
                e.preventDefault();
                return false; //exit fallback
            }

            if(document.getElementById("login_anv").value.length > 30){
                //false, to long string exeding 30 chars

                alert('Username exceeds 30 chars');
                e.preventDefault();
                return false;
            }
            if(document.getElementById("login_lsn").value.length > 30){
                //false, to long string exeding 30 chars

                alert('Password exceeds 30 chars');
                e.preventDefault();
                return false;
            }
            return true; //exit the fallback function
        })
    }
})

