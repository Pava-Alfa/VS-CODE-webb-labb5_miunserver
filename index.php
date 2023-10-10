<?php

/*
jeer1902 23/4-23
project: Warframe in-game status application
*/

//page title and including 'header' which containing 'head' tag and an 'header' tag 
$page_title = "Home";
include("includes/header.php");

/*
    including php script that manages the creation of the left side of the zone between the 'header' and 'footer' tags. 
    This zone is a div tag 'grid2' and the left side is a 'section' tag 'leftcontent'.
*/

include("includes/main_section.php");
?>





<?php

/*
    including php script that manages the creation of the right side of the zone between the 'header' and 'footer' tags. 
    This zone is a div tag 'grid2' and the right side is a 'section' tag 'sidebar'.
*/

include("includes/sidebar.php");

/*
    including php script that manages the creation of the footer underneath the div tag 'grid2'. 
    This zone is a div tag 'grid2' and the right side is a 'footer' tag 'mainfooter'.
*/

include("includes/index_footer.php");
?>
