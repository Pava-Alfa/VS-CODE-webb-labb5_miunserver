<!--
    jeer1902 24/4-23
    project: Warframe in-game status application
-->

<footer id="mainfooter">
    <!-- Design for footer -->
    <hr class='activity_separor' />
    <hr class='activity_separor' />
</footer><!-- /mainfooter -->

<!-- calls js wich works with the builded webpage elements-->
<script src="js/main.js"></script> 

<!--convert php array to json notated array, functions cant be be called from the script tag with a 'src' attribute -->
<script>
    write_in_sidebar(<?= json_encode($sidebar_array_2); ?>);
    write_in_main_section(<?= json_encode($user_activities); ?>);
</script> 
    
</body>
</html>