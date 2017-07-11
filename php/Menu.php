<?php
// purpose: provides a unified script for presenting the appropriate menu at the top of a page

include "../config/dbConfig.php";
global $conn;
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

  $superAdminNav = '<ul class="topnav" id="adminNav">
    <li><a href="/pages/home.php">Home</a></li>
    <li><a href="/pages/projects.php">Projects</a></li>
    <!--<li><a href="#printers">Printers</a></li>-->
    <li><a href="/pages/request.php">Request</a></li>
    <li><a href="/pages/resources.php">Resources</a></li>
    <li><a href="/pages/feedback.php">Feedback</a></li>
    <li><a href="/pages/about.php">About</a></li>
    <li><a href="/pages/stats.php">Stats</a></li>
    <li><a href="/pages/accountsettings.php">Account Settings</a></li>
    <li><a href="/pages/databasesettings.php">Database Settings</a></li>
    <li><a href="/php/logout.php">Logout</a></li>
  </ul>';

  $adminNav = '<ul class="topnav" id="adminNav">
    <li><a href="/pages/home.php">Home</a></li>
    <li><a href="/pages/projects.php">Projects</a></li>
    <!--<li><a href="#printers">Printers</a></li>-->
    <li><a href="/pages/request.php">Request</a></li>
    <li><a href="/pages/resources.php">Resources</a></li>
    <li><a href="/pages/feedback.php">Feedback</a></li>
    <li><a href="/pages/about.php">About</a></li>
    <li><a href="/pages/stats.php">Stats</a></li>
    <li><a href="/pages/accountsettings.php">Account Settings</a></li>
    <li><a href="/php/logout.php">Logout</a></li>
  </ul>';

  $studentNav = '<ul class="topnav" id="studentNave">
    <li><a href="/pages/home.php">Home</a></li>
    <li><a href="/pages/request.php">Request</a></li>
    <li><a href="/pages/resources.php">Resources</a></li>
    <li><a href="/pages/feedback.php">Feedback</a></li>
    <li><a href="/pages/about.php">About</a></li>
    <li><a href="/pages/accountsettings.php">Account Settings</a></li>
    <li><a href="/php/logout.php">Logout</a></li>
  </ul>';

  //admin check
  $usertype = require ("../php/AdminCheck.php");

  if(strcmp($usertype,'SUPER-Admin') == 0){
    echo $superAdminNav;
  }
  else if(strcmp($usertype,'Admin') == 0){
    echo $adminNav;
  }
  else
  {
    echo $studentNav;
  }

}
?>
