<?php
// purpose: provides the project ID of the project clicked on for management 
$ID = $_GET['id'];

$cookie_name = 'ProjectID';
$cookie_value = $ID;
setcookie($cookie_name, $cookie_value,0,'/');

header("Refresh:0; url=/pages/manage.php");
?>
